<?php

namespace WPSettingsKit\Domain\Renderer;

use WPSettingsKit\Domain\Field\Base\AbstractField;
use WPSettingsKit\Domain\Field\Base\Interface\IFieldRenderer;

/**
 * Renders textarea fields.
 */
class TextareaRenderer implements IFieldRenderer
{
    /**
     * @var int The number of rows in the textarea.
     */
    private int $rows;

    /**
     * Constructor for TextareaRenderer.
     *
     * @param int $rows The number of rows for the textarea.
     */
    public function __construct(int $rows = 5)
    {
        $this->rows = $rows;
    }

    /**
     * Renders a textarea field as HTML.
     *
     * @param AbstractField $field The field to render.
     * @param array<string, mixed> $attributes The HTML attributes for the textarea.
     * @return string The rendered HTML markup.
     */
    public function render(AbstractField $field, array $attributes): string
    {
        $attributes['rows'] = $this->rows;
        $html = sprintf(
            '<textarea %s>%s</textarea>',
            $field->buildAttributeString($attributes),
            esc_textarea($field->getValue() ?? '')
        );
        $enhancer = (fn() => $this->enhancer)->call($field);
        $html = $enhancer ? $enhancer->decorate($html, $field) : $html;
        return apply_filters('wp_settings_field_render_textarea', $html, $field, $attributes);
    }
}