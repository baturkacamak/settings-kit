<?php

namespace WPSettingsKit\Field;

/**
 * Select/dropdown field implementation
 *
 * Represents a dropdown menu with selectable options.
 */
class SelectField extends AbstractField
{
    /**
     * @var array The options available in the select field (value => label)
     */
    protected array $options;

    /**
     * Constructor
     *
     * @param array $config Configuration array for the select field
     */
    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->options = $config['options'] ?? [];
    }

    /**
     * Renders the select field as HTML
     *
     * @return string The rendered HTML markup
     */
    public function render(): string
    {
        $attributes = [
            'name' => $this->getKey(),
            'id' => $this->getKey(),
            'class' => 'regular-select',
            'required' => $this->isRequired() ? 'required' : null,
        ];

        $html = '<select ' . $this->buildAttributeString($attributes) . '>';

        foreach ($this->options as $value => $label) {
            $selected = $this->getValue() == $value ? ' selected' : '';
            $html .= sprintf(
                '<option value="%s"%s>%s</option>',
                htmlspecialchars($value),
                $selected,
                htmlspecialchars($label)
            );
        }

        $html .= '</select>';

        return $this->decorator ? $this->decorator->decorate($html, $this) : $html;
    }

    /**
     * Sanitizes the select field value
     *
     * @return mixed The sanitized value, or null if not a valid option
     */
    public function sanitize(): mixed
    {
        $value = $this->getValue();
        return isset($this->options[$value]) ? $value : null;
    }
}