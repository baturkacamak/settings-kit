<?php

namespace WPSettingsKit\Builder\Decorator\CheckboxField;

use WPSettingsKit\Builder\Interface\IFieldBuilderDecorator;


/**
 * Decorator for setting unchecked value on checkbox fields
 */
class UncheckedValueDecorator implements IFieldBuilderDecorator
{
    /**
     * @var mixed Value when checkbox is unchecked
     */
    private mixed $uncheckedValue;

    /**
     * Constructor
     *
     * @param mixed $uncheckedValue Value when checkbox is unchecked
     */
    public function __construct(mixed $uncheckedValue)
    {
        $this->uncheckedValue = $uncheckedValue;
    }

    /**
     * Apply unchecked value to configuration
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Updated configuration
     */
    public function applyToConfig(array $config): array
    {
        $config['unchecked_value'] = $this->uncheckedValue;
        return $config;
    }
}