<?php

namespace WPSettingsKit\Builder\Decorator;

use WPSettingsKit\Builder\Interface\IFieldBuilderDecorator;

/**
 * Decorator for making field required
 */
class RequiredDecorator implements IFieldBuilderDecorator
{
    /**
     * @var bool Whether the field is required
     */
    private bool $required;

    /**
     * Constructor
     *
     * @param bool $required Whether to make the field required
     */
    public function __construct(bool $required = true)
    {
        $this->required = $required;
    }

    /**
     * Apply required state to configuration
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Updated configuration
     */
    public function applyToConfig(array $config): array
    {
        $config['required'] = $this->required;
        return $config;
    }
}