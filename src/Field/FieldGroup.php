<?php

namespace WPSettingsKit\Field;

use WPSettingsKit\Exception\ValidationException;
use WPSettingsKit\Field\Interface\IField;

/**
 * Field group implementation
 *
 * Represents a collection of fields rendered together as a group.
 */
class FieldGroup extends AbstractField
{
    /**
     * @var array<IField> The fields in the group
     */
    private array $fields = [];

    /**
     * Adds a field to the group
     *
     * @param IField $field The field to add
     */
    public function addField(IField $field): void
    {
        $this->fields[] = $field;
    }

    /**
     * Gets all fields in the group
     *
     * @return array<IField> The list of fields
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * Renders the field group as HTML
     *
     * @return string The rendered HTML markup
     */
    public function render(): string
    {
        $html = '<div class="field-group">';

        foreach ($this->fields as $field) {
            $html .= '<div class="field-group-item">';
            $html .= sprintf(
                '<label for="%s">%s</label>',
                esc_attr($field->getKey()),
                esc_html($field->getLabel())
            );
            $html .= $field->render();
            $html .= '</div>';
        }

        $html .= '</div>';

        return $this->decorator ? $this->decorator->decorate($html, $this) : $html;
    }

    /**
     * Validates all fields in the group and the group itself
     *
     * @return bool True if validation passes
     * @throws ValidationException If any field or the group fails validation
     */
    public function validate(): bool
    {
        $errors = [];
        foreach ($this->fields as $field) {
            try {
                $field->validate();
            } catch (ValidationException $e) {
                $errors[] = $e->getMessage();
            }
        }
        try {
            parent::validate();
        } catch (ValidationException $e) {
            $errors[] = $e->getMessage();
        }
        if (!empty($errors)) {
            throw new ValidationException("Field group '{$this->key}' validation failed: " . implode('; ', $errors));
        }
        return true;
    }

    /**
     * Sanitizes the values of all fields in the group
     *
     * @return array<string, mixed> An array of sanitized values keyed by field keys
     */
    public function sanitize(): array
    {
        $values = [];
        foreach ($this->fields as $field) {
            $values[$field->getKey()] = $field->sanitize();
        }
        return $values;
    }
}