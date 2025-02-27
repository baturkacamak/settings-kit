<?php

namespace WPSettingsKit\Builder\Decorator;

use WPSettingsKit\Builder\Interface\IFieldBuilderDecorator;

/**
 * Decorator for adding a default value to fields
 */
class DefaultValueDecorator implements IFieldBuilderDecorator
{
    /**
     * @var mixed Default value
     */
    private mixed $defaultValue;

    /**
     * Constructor
     *
     * @param mixed $defaultValue Default value for the field
     */
    public function __construct(mixed $defaultValue)
    {
        $this->defaultValue = $defaultValue;
    }

    /**
     * Apply default value to configuration
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Updated configuration
     */
    public function applyToConfig(array $config): array
    {
        // Only set value if not already set
        if (!isset($config['value'])) {
            $config['value'] = $this->defaultValue;
        }

        return $config;
    }
}