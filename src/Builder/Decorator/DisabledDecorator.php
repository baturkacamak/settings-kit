<?php

namespace WPSettingsKit\Builder\Decorator;

use WPSettingsKit\Builder\Interface\IFieldBuilderDecorator;

/**
 * Decorator for adding disabled state to fields
 */
class DisabledDecorator implements IFieldBuilderDecorator
{
    /**
     * @var bool Whether the field is disabled
     */
    private bool $disabled;

    /**
     * Constructor
     *
     * @param bool $disabled Whether to disable the field
     */
    public function __construct(bool $disabled = true)
    {
        $this->disabled = $disabled;
    }

    /**
     * Apply disabled state to configuration
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Updated configuration
     */
    public function applyToConfig(array $config): array
    {
        $config['disabled'] = $this->disabled;
        return $config;
    }
}