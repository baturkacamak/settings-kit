<?php

namespace WPSettingsKit\Field;

use WPSettingsKit\Field\Interface\IFieldRenderer;

/**
 * Renders input fields such as text or checkbox.
 */
class InputRenderer implements IFieldRenderer
{
    /**
     * Renders an input field as HTML.
     *
     * @param AbstractField $field The field to render.
     * @param array<string, mixed> $attributes The HTML attributes for the input.
     * @return string The rendered HTML markup.
     */
    public function render(AbstractField $field, array $attributes): string
    {
        $html = sprintf('<input %s>', $field->buildAttributeString($attributes));
        $decorator = (fn() => $this->decorator)->call($field);
        $html = $decorator ? $decorator->decorate($html, $field) : $html;
        return apply_filters('wp_settings_field_render_input', $html, $field, $attributes);
    }
}