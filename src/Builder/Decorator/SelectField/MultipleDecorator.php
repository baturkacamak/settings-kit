<?php

namespace WPSettingsKit\Builder\Decorator\SelectField;

use WPSettingsKit\Builder\Interface\IFieldBuilderDecorator;

/**
 * Decorator for setting multiple select capability
 */
class MultipleDecorator implements IFieldBuilderDecorator
{
    /**
     * @var bool Whether multiple selections are allowed
     */
    private bool $multiple;

    /**
     * Constructor
     *
     * @param bool $multiple Whether to allow multiple selections
     */
    public function __construct(bool $multiple = true)
    {
        $this->multiple = $multiple;
    }

    /**
     * Apply multiple selection capability to configuration
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Updated configuration
     */
    public function applyToConfig(array $config): array
    {
        $config['multiple'] = $this->multiple;
        return $config;
    }
}