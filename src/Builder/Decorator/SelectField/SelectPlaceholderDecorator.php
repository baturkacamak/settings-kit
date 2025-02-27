<?php

namespace WPSettingsKit\Builder\Decorator\SelectField;

use WPSettingsKit\Builder\Interface\IFieldBuilderDecorator;

/**
 * Decorator for adding placeholder option to select fields
 */
class SelectPlaceholderDecorator implements IFieldBuilderDecorator
{
    /**
     * @var string Placeholder text
     */
    private string $placeholder;

    /**
     * @var bool Whether placeholder option is disabled
     */
    private bool $disabled;

    /**
     * Constructor
     *
     * @param string $placeholder Placeholder text
     * @param bool $disabled Whether placeholder option is disabled
     */
    public function __construct(string $placeholder, bool $disabled = true)
    {
        $this->placeholder = $placeholder;
        $this->disabled = $disabled;
    }

    /**
     * Apply placeholder option to configuration
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Updated configuration
     */
    public function applyToConfig(array $config): array
    {
        $config['placeholder'] = $this->placeholder;
        $config['placeholder_disabled'] = $this->disabled;
        return $config;
    }
}