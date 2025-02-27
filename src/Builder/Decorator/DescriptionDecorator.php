<?php

namespace WPSettingsKit\Builder\Decorator;

use WPSettingsKit\Builder\Interface\IFieldBuilderDecorator;

/**
 * Decorator for adding a description to fields
 */
class DescriptionDecorator implements IFieldBuilderDecorator
{
    /**
     * @var string Description text
     */
    private string $description;

    /**
     * Constructor
     *
     * @param string $description Field description text
     */
    public function __construct(string $description)
    {
        $this->description = $description;
    }

    /**
     * Apply description to configuration
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Updated configuration
     */
    public function applyToConfig(array $config): array
    {
        $config['description'] = $this->description;
        return $config;
    }
}