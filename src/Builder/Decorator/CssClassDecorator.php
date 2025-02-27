<?php

namespace WPSettingsKit\Builder\Decorator;

use WPSettingsKit\Builder\Interface\IFieldBuilderDecorator;

/**
 * Decorator for adding CSS classes to fields
 */
class CssClassDecorator implements IFieldBuilderDecorator
{
    /**
     * @var string CSS classes to add
     */
    private string $cssClass;

    /**
     * Constructor
     *
     * @param string $cssClass CSS classes to add
     */
    public function __construct(string $cssClass)
    {
        $this->cssClass = $cssClass;
    }

    /**
     * Apply CSS classes to configuration
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Updated configuration
     */
    public function applyToConfig(array $config): array
    {
        $existing            = $config['css_class'] ?? '';
        $config['css_class'] = trim($existing . ' ' . $this->cssClass);
        return $config;
    }
}