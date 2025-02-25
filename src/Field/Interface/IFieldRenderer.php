<?php

namespace WPSettingsKit\Field\Interface;

use WPSettingsKit\Field\AbstractField;

/**
 * Interface for rendering field HTML markup.
 */
interface IFieldRenderer
{
    /**
     * Renders the field's HTML markup.
     *
     * @param AbstractField $field The field to render.
     * @param array<string, mixed> $attributes The HTML attributes for the field.
     * @return string The rendered HTML markup.
     */
    public function render(AbstractField $field, array $attributes): string;
}