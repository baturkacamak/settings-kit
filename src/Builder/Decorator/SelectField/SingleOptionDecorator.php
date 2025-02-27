<?php

namespace WPSettingsKit\Builder\Decorator\SelectField;

use WPSettingsKit\Builder\Interface\IFieldBuilderDecorator;

/**
 * Decorator for adding a single option to select fields
 */
class SingleOptionDecorator implements IFieldBuilderDecorator
{
    /**
     * @var string Option key
     */
    private string $key;

    /**
     * @var string Option label
     */
    private string $label;

    /**
     * Constructor
     *
     * @param string $key Option key/value
     * @param string $label Option display label
     */
    public function __construct(string $key, string $label)
    {
        $this->key = $key;
        $this->label = $label;
    }

    /**
     * Apply single option to configuration
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Updated configuration
     */
    public function applyToConfig(array $config): array
    {
        if (!isset($config['options'])) {
            $config['options'] = [];
        }

        $config['options'][$this->key] = $this->label;
        return $config;
    }
}