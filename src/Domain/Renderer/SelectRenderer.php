<?php

namespace WPSettingsKit\Domain\Renderer;

use WPSettingsKit\Domain\Field\Base\AbstractField;
use WPSettingsKit\Domain\Field\Base\Interface\IFieldRenderer;

/**
 * Renders select (dropdown) fields.
 */
class SelectRenderer implements IFieldRenderer
{
    /**
     * @var array<string, string> The options for the select (value => label).
     */
    private array $options;

    /**
     * Constructor for SelectRenderer.
     *
     * @param array<string, string> $options The select options.
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * Renders a select field as HTML.
     *
     * @param AbstractField $field The field to render.
     * @param array<string, mixed> $attributes The HTML attributes for the select.
     * @return string The rendered HTML markup.
     */
    public function render(AbstractField $field, array $attributes): string
    {
        $html = sprintf('<select %s>', $field->buildAttributeString($attributes));
        foreach ($this->options as $value => $label) {
            $selected = $field->getValue() == $value ? ' selected' : '';
            $html .= sprintf(
                '<option value="%s"%s>%s</option>',
                htmlspecialchars($value),
                $selected,
                htmlspecialchars($label)
            );
        }
        $html .= '</select>';
        $enhancer = (fn() => $this->enhancer)->call($field);
        $html = $enhancer ? $enhancer->decorate($html, $field) : $html;
        return apply_filters('wp_settings_field_render_select', $html, $field, $attributes, $this->options);
    }
}