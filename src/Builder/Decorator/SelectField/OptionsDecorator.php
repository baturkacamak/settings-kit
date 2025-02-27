<?php

namespace WPSettingsKit\Builder\Decorator\SelectField;

use WPSettingsKit\Builder\Interface\IFieldBuilderDecorator;

/**
 * Decorator for adding options to select fields
 */
class OptionsDecorator implements IFieldBuilderDecorator
{
    /**
     * @var array<string, mixed> Select options (key => label)
     */
    private array $options;

    /**
     * Constructor
     *
     * @param array<string, mixed> $options Select options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * Apply options to configuration
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Updated configuration
     */
    public function applyToConfig(array $config): array
    {
        // Merge with any existing options
        $existingOptions   = $config['options'] ?? [];
        $config['options'] = array_merge($existingOptions, $this->options);

        return $config;
    }
}