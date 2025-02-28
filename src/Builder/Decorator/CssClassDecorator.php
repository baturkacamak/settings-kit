<?php

namespace WPSettingsKit\Builder\Decorator\Common;

use WPSettingsKit\Attribute\FieldDecorator;
use WPSettingsKit\Builder\Decorator\AbstractFieldBuilderDecorator;

/**
 * Decorator for adding CSS classes to fields with flexible configuration.
 */
#[FieldDecorator(
    type: 'all',
    method: 'setCssClass',
    priority: 80
)]
class CssClassDecorator extends AbstractFieldBuilderDecorator {
    private string $cssClass;
    private bool $replace;

    public function __construct(string $cssClass, bool $replace = false, ?int $priority = null) {
        parent::__construct($priority);
        $this->cssClass = $cssClass;
        $this->replace = $replace;
    }

    protected function applyCustomLogic(array $config): array {
        // Special handling for css classes to handle replace/append behavior
        if (!isset($config['css_class']) || $this->replace) {
            $config['css_class'] = $this->cssClass;
        } else {
            $config['css_class'] = trim($config['css_class'] . ' ' . $this->cssClass);
        }

        // Handle HTML class attribute with special logic
        if (!isset($config['attributes'])) {
            $config['attributes'] = [];
        }

        if (!isset($config['attributes']['class']) || $this->replace) {
            $config['attributes']['class'] = $this->cssClass;
        } else {
            $config['attributes']['class'] = trim($config['attributes']['class'] . ' ' . $this->cssClass);
        }

        return $config;
    }
}