<?php

namespace WPSettingsKit\Builder;

use ReflectionClass;
use ReflectionMethod;
use WPSettingsKit\Builder\Interface\IFieldBuilder;
use WPSettingsKit\Builder\Interface\IFieldBuilderDecorator;
use WPSettingsKit\Exception\BuilderException;
use WPSettingsKit\Registry\DecoratorRegistry;
use WPSettingsKit\Validation\Base\Interface\IValidationRule;

/**
 * Base field builder with automatic decorator discovery and application.
 *
 * Implements the builder pattern for creating field objects with a fluent interface.
 * Supports PHP 8 attributes and automatic decorator discovery.
 */
abstract class BaseFieldBuilder implements IFieldBuilder
{
    /**
     * @var array<string, mixed> Core configuration for the field
     */
    protected array $config = [];

    /**
     * @var array<IFieldBuilderDecorator> Decorators to apply
     */
    protected array $decorators = [];

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

        // Ensure decorators have been discovered
        $this->setupAutomaticDecorators();
    }

    /**
     * Sets up automatic decorator application through magic method interception.
     *
     * @return void
     */
    protected function setupAutomaticDecorators(): void
    {
        // Ensure decorators have been discovered
        if (!DecoratorRegistry::isDiscovered()) {
            DecoratorRegistry::discoverDecorators('WPSettingsKit\\Builder\\Decorator');
        }
    }

    /**
     * Intercepts method calls to apply automatic decorators.
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

        // Then try to find a decorator for this method
        $decoratorClass = DecoratorRegistry::getDecorator($this->fieldType, $name);

        if ($decoratorClass !== null) {
            // Create and apply the decorator
            $decorator = $this->createDecoratorInstance($decoratorClass, $arguments);
            return $this->addDecorator($decorator);
        }

        throw new BuilderException("Method '{$name}' not found in " . get_class($this));
    }

    /**
     * Creates a decorator instance with the provided arguments.
     *
     * @param string $decoratorClass The decorator class name
     * @param array<mixed> $arguments Constructor arguments
     * @return IFieldBuilderDecorator The decorator instance
     * @throws BuilderException If creation fails
     */
    protected function createDecoratorInstance(string $decoratorClass, array $arguments): IFieldBuilderDecorator
    {
        try {
            $reflectionClass = new ReflectionClass($decoratorClass);

            if (!$reflectionClass->implementsInterface(IFieldBuilderDecorator::class)) {
                throw new BuilderException("Class '{$decoratorClass}' does not implement IFieldBuilderDecorator");
            }

            return $reflectionClass->newInstanceArgs($arguments);
        } catch (\ReflectionException $e) {
            throw new BuilderException("Failed to create decorator instance: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Add a decorator to the builder
     *
     * @param IFieldBuilderDecorator $decorator Decorator to add
     * @return self
     */
    public function addDecorator(IFieldBuilderDecorator $decorator): self
    {
        $this->decorators[] = $decorator;
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
     * Gets all available builder methods for this field type, including automatic decorators.
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

        // Get decorator methods
        $decorators = DecoratorRegistry::getDecoratorsForType($this->fieldType);
        foreach ($decorators as $method => $decoratorClasses) {
            $methods[] = $method;
        }

        return array_unique($methods);
    }

    /**
     * Get the complete configuration with decorators applied
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

        // Apply all decorators to the configuration
        foreach ($this->decorators as $decorator) {
            $finalConfig = $decorator->applyToConfig($finalConfig);
        }

        return $finalConfig;
    }
}