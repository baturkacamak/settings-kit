<?php

namespace WPSettingsKit\Field\Basic;

use WPSettingsKit\Field\Base\AbstractField;
use WPSettingsKit\Field\Base\Interface\IFieldRenderer;
use WPSettingsKit\Field\Renderer\InputRenderer;

/**
 * Checkbox field implementation
 *
 * Represents a checkbox input field with checked and unchecked values.
 */
class CheckboxField extends AbstractField
{
    /**
     * @var mixed The value when the checkbox is checked
     */
    protected mixed $checkedValue;

    /**
     * @var mixed The value when the checkbox is unchecked
     */
    protected mixed $uncheckedValue;

    /**
     * Constructor
     *
     * @param array $config Configuration array for the checkbox field
     */
    public function __construct(array $config)
    {
        $this->checkedValue   = $config['checked_value'] ?? true;
        $this->uncheckedValue = $config['unchecked_value'] ?? false;

        parent::__construct(
            $config['key'] ?? '',
            $config['label'] ?? '',
            $config['value'] ?? null,
            $config['required'] ?? false,
            $config['description'] ?? '',
            $config['validation_rules'] ?? [],
            $config['dependencies'] ?? [],
            $config['transformer'] ?? null,
            $config['enhancer'] ?? null,
            $config['event_dispatcher'] ?? null,
            $config['renderer'] ?? null
        );
    }

    /**
     * Renders the checkbox field as HTML
     *
     * @return string The rendered HTML markup
     */
    public function render(): string
    {
        $attributes = [
            'type'     => 'checkbox',
            'name'     => $this->getKey(),
            'id'       => $this->getKey(),
            'value'    => $this->checkedValue,
            'class'    => 'regular-checkbox',
            'checked'  => $this->getValue() == $this->checkedValue ? 'checked' : null,
            'required' => $this->isRequired() ? 'required' : null,
        ];

        $html = '<input ' . $this->buildAttributeString($attributes) . '>';

        // Add hidden field to ensure unchecked value is submitted
        $html .= sprintf(
            '<input type="hidden" name="%s_unchecked" value="%s">',
            $this->getKey(),
            htmlspecialchars($this->uncheckedValue)
        );

        return $this->enhancer ? $this->enhancer->decorate($html, $this) : $html;
    }

    /**
     * Sanitizes the checkbox field value
     *
     * @return mixed The sanitized value (checked or unchecked value)
     */
    public function sanitize(): mixed
    {
        return $this->getValue() == $this->checkedValue ? $this->checkedValue : $this->uncheckedValue;
    }

    /**
     * Provides the default renderer for the checkbox field.
     *
     * @return IFieldRenderer The default renderer instance.
     */
    protected function getDefaultRenderer(): IFieldRenderer
    {
        return new InputRenderer();
    }
}