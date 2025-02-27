<?php

namespace WPSettingsKit\Builder\Decorator\CheckboxField;

use WPSettingsKit\Builder\Interface\IFieldBuilderDecorator;

/**
 * Decorator for setting checked value on checkbox fields
 */
class CheckedValueDecorator implements IFieldBuilderDecorator
{
    /**
     * @var mixed Value when checkbox is checked
     */
    private mixed $checkedValue;

    /**
     * Constructor
     *
     * @param mixed $checkedValue Value when checkbox is checked
     */
    public function __construct(mixed $checkedValue)
    {
        $this->checkedValue = $checkedValue;
    }

    /**
     * Apply checked value to configuration
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Updated configuration
     */
    public function applyToConfig(array $config): array
    {
        $config['checked_value'] = $this->checkedValue;
        return $config;
    }
}