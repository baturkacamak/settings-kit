<?php

namespace WPSettingsKit\Builder\Decorator\TextField;

use WPSettingsKit\Builder\Interface\IFieldBuilderDecorator;

/**
 * Decorator for setting input type on text fields
 */
class InputTypeDecorator implements IFieldBuilderDecorator
{
    /**
     * @var string HTML input type
     */
    private string $inputType;

    /**
     * @var array<string> Valid input types
     */
    private array $validTypes = ['text', 'email', 'url', 'tel', 'password', 'number', 'search'];

    /**
     * Constructor
     *
     * @param string $inputType HTML input type
     */
    public function __construct(string $inputType)
    {
        $this->inputType = in_array($inputType, $this->validTypes) ? $inputType : 'text';
    }

    /**
     * Apply input type to configuration
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Updated configuration
     */
    public function applyToConfig(array $config): array
    {
        $config['input_type'] = $this->inputType;
        return $config;
    }
}