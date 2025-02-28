<?php

namespace WPSettingsKit\Builder\Decorator\Common;

use WPSettingsKit\Attribute\FieldDecorator;
use WPSettingsKit\Builder\Decorator\AbstractFieldBuilderDecorator;

/**
 * Decorator for adding CSS classes to fields.
 *
 * This decorator can be applied to all field types.
 */
#[FieldDecorator(
    type: 'all',
    method: 'setCssClass',
    priority: 80
)]
class CssClassDecorator extends AbstractFieldBuilderDecorator
{
    /**
     * @var string CSS classes to add
     */
    private string $cssClass;

    /**
     * @var bool Whether to replace existing classes
     */
    private bool $replace;

    /**
     * Constructor.
     *
     * @param string $cssClass CSS classes to add
     * @param bool $replace Whether to replace existing classes
     * @param int|null $priority Optional priority override
     */
    public function __construct(string $cssClass, bool $replace = false, ?int $priority = null)
    {
        parent::__construct($priority);
        $this->cssClass = $cssClass;
        $this->replace = $replace;
    }

    /**
     * {@inheritdoc}
     */
    public function applyToConfig(array $config): array
    {
        // Special handling for css classes to handle replace/append behavior
        if (!isset($config['css_class']) || $this->replace) {
            $config['css_class'] = $this->cssClass;
        } else {
            $config['css_class'] = trim($config['css_class'] . ' ' . $this->cssClass);
        }

        // Handle HTML class attribute similarly
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

    /**
     * {@inheritdoc}
     */
    protected function getConfigModifications(): array
    {
        // This is not used in the CssClassDecorator since it has
        // special handling in applyToConfig
        return [];
    }
}