<?php

namespace WPSettingsKit\Builder\Decorator\CheckboxField;

use WPSettingsKit\Builder\Interface\IFieldBuilderDecorator;

/**
 * Decorator for styling checkbox as switch
 */
class SwitchStyleDecorator implements IFieldBuilderDecorator
{
    /**
     * @var bool Whether to style as switch
     */
    private bool $switchStyle;

    /**
     * Constructor
     *
     * @param bool $switchStyle Whether to style checkbox as switch
     */
    public function __construct(bool $switchStyle = true)
    {
        $this->switchStyle = $switchStyle;
    }

    /**
     * Apply switch style to configuration
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Updated configuration
     */
    public function applyToConfig(array $config): array
    {
        $config['switch_style'] = $this->switchStyle;
        return $config;
    }
}