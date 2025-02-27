<?php

namespace WPSettingsKit\Builder\Decorator\TextField;

use WPSettingsKit\Builder\Interface\IFieldBuilderDecorator;

/**
 * Decorator for adding placeholder to text fields
 */
class PlaceholderDecorator implements IFieldBuilderDecorator
{
    /**
     * @var string Placeholder text
     */
    private string $placeholder;

    /**
     * Constructor
     *
     * @param string $placeholder Placeholder text
     */
    public function __construct(string $placeholder)
    {
        $this->placeholder = $placeholder;
    }

    /**
     * Apply placeholder to configuration
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Updated configuration
     */
    public function applyToConfig(array $config): array
    {
        $config['placeholder'] = $this->placeholder;
        return $config;
    }
}