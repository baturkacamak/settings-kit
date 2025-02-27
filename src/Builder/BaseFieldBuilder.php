<?php

namespace WPSettingsKit\Builder;

use WPSettingsKit\Builder\Interface\IFieldBuilder;
use WPSettingsKit\Builder\Interface\IFieldBuilderDecorator;
use WPSettingsKit\Exception\BuilderException;
use WPSettingsKit\Validation\Base\Interface\IValidationRule;

/**
 * Base field builder with decorator support
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
     * Constructor
     *
     * @param string $key Field unique key
     * @param string $label Field display label
     */
    public function __construct(string $key, string $label)
    {
        $this->config['key']   = $key;
        $this->config['label'] = $label;
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
}