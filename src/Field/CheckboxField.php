<?php

namespace WPSettingsKit\Field;

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
        parent::__construct($config);
        $this->checkedValue = $config['checked_value'] ?? true;
        $this->uncheckedValue = $config['unchecked_value'] ?? false;
    }

    /**
     * Renders the checkbox field as HTML
     *
     * @return string The rendered HTML markup
     */
    public function render(): string
    {
        $attributes = [
            'type' => 'checkbox',
            'name' => $this->getKey(),
            'id' => $this->getKey(),
            'value' => $this->checkedValue,
            'class' => 'regular-checkbox',
            'checked' => $this->getValue() == $this->checkedValue ? 'checked' : null,
            'required' => $this->isRequired() ? 'required' : null,
        ];

        $html = '<input ' . $this->buildAttributeString($attributes) . '>';

        // Add hidden field to ensure unchecked value is submitted
        $html .= sprintf(
            '<input type="hidden" name="%s_unchecked" value="%s">',
            $this->getKey(),
            htmlspecialchars($this->uncheckedValue)
        );

        return $this->decorator ? $this->decorator->decorate($html, $this) : $html;
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
}