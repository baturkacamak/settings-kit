<?php

namespace WPSettingsKit\Domain\Field\Builder;

use ReflectionClass;
use ReflectionMethod;
use WPSettingsKit\Domain\Exception\BuilderException;
use WPSettingsKit\Domain\Field\Builder\Interface\IFieldBuilder;
use WPSettingsKit\Domain\Field\Builder\Interface\IFieldBuilderEnhancer;
use WPSettingsKit\Domain\Registry\EnhancerRegistry;
use WPSettingsKit\Domain\Validation\Base\Interface\IValidationRule;

/**
 * Base field builder with automatic enhancer discovery and application.
 *
 * Implements the builder pattern for creating field objects with a fluent interface.
 * Supports PHP 8 attributes and automatic enhancer discovery.
 */
abstract class BaseFieldBuilder implements IFieldBuilder
{
    /**
     * @var array<string, mixed> Core configuration for the field
     */
    protected array $config = [];

    /**
     * @var array<IFieldBuilderEnhancer> enhancers to apply
     */
    protected array $enhancers = [];

    /**
     * @var string The field type (text, select, checkbox, etc.)
     */
    protected string $fieldType;

    /**
     * Constructor.
     *
     * @param string $key Field unique key
     * @param string $label Field display label
     * @param string $fieldType Field type
     */
    public function __construct(string $key, string $label, string $fieldType)
    {
        $this->config['key']   = $key;
        $this->config['label'] = $label;
        $this->fieldType       = $fieldType;

        // Ensure enhancers have been discovered
        $this->setupAutomaticEnhancers();
    }

    /**
     * Sets up automatic enhancer application through magic method interception.
     *
     * @return void
     */
    protected function setupAutomaticEnhancers(): void
    {
        // Ensure enhancers have been discovered
        if (!EnhancerRegistry::isDiscovered()) {
            EnhancerRegistry::discoverEnhancers('WPSettingsKit\\Builder\\Enhancer');
        }
    }

    /**
     * Intercepts method calls to apply automatic enhancers.
     *
     * @param string $name Method name
     * @param array<mixed> $arguments Method arguments
     * @return self For method chaining
     * @throws BuilderException If method is not found
     */
    public function __call(string $name, array $arguments): self
    {
        // First check if the method exists in the class
        if (method_exists($this, $name)) {
            return call_user_func_array([$this, $name], $arguments);
        }

        // Then try to find a enhancer for this method
        $enhancerClass = EnhancerRegistry::getEnhancer($this->fieldType, $name);

        if ($enhancerClass !== null) {
            // Create and apply the enhancer
            $enhancer = $this->createEnhancerInstance($enhancerClass, $arguments);
            return $this->addEnhancer($enhancer);
        }

        throw new BuilderException("Method '{$name}' not found in " . get_class($this));
    }

    /**
     * Creates a enhancer instance with the provided arguments.
     *
     * @param string $enhancerClass The enhancer class name
     * @param array<mixed> $arguments Constructor arguments
     * @return IFieldBuilderEnhancer The enhancer instance
     * @throws BuilderException If creation fails
     */
    protected function createEnhancerInstance(string $enhancerClass, array $arguments): IFieldBuilderEnhancer
    {
        try {
            $reflectionClass = new ReflectionClass($enhancerClass);

            if (!$reflectionClass->implementsInterface(IFieldBuilderEnhancer::class)) {
                throw new BuilderException("Class '{$enhancerClass}' does not implement IFieldBuilderEnhancer");
            }

            return $reflectionClass->newInstanceArgs($arguments);
        } catch (\ReflectionException $e) {
            throw new BuilderException("Failed to create enhancer instance: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Add a enhancer to the builder
     *
     * @param IFieldBuilderEnhancer $enhancer Enhancer to add
     * @return self
     */
    public function addEnhancer(IFieldBuilderEnhancer $enhancer): self
    {
        $this->enhancers[] = $enhancer;
        return $this;
    }

    /**
     * Add a validation rule
     *
     * @param IValidationRule $rule
     * @return self
     */
    public function addValidationRule(IValidationRule $rule): self
    {
        if (!isset($this->config['validation_rules'])) {
            $this->config['validation_rules'] = [];
        }

        $this->config['validation_rules'][] = $rule;
        return $this;
    }

    /**
     * Gets the field type.
     *
     * @return string The field type
     */
    public function getFieldType(): string
    {
        return $this->fieldType;
    }

    /**
     * Gets all available builder methods for this field type, including automatic enhancers.
     *
     * @return array<string> Array of method names
     */
    public function getAvailableMethods(): array
    {
        $methods = [];

        // Get native methods
        $reflectionClass = new ReflectionClass($this);
        foreach ($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if (!$method->isStatic() && $method->getDeclaringClass()->getName() !== IFieldBuilder::class) {
                $methods[] = $method->getName();
            }
        }

        // Get enhancer methods
        $enhancers = EnhancerRegistry::getEnhancersForType($this->fieldType);
        foreach ($enhancers as $method => $enhancer) {
            $methods[] = $method;
        }

        return array_unique($methods);
    }

    /**
     * Get the complete configuration with enhancers applied
     *
     * @return array<string, mixed> Complete field configuration
     * @throws BuilderException If configuration is invalid
     */
    protected function getDecoratedConfig(): array
    {
        // Validate required configuration
        if (empty($this->config['key'])) {
            throw new BuilderException("Field key is required");
        }

        $finalConfig = $this->config;

        // Apply all enhancers to the configuration
        foreach ($this->enhancers as $enhancer) {
            $finalConfig = $enhancer->applyToConfig($finalConfig);
        }

        return $finalConfig;
    }
}