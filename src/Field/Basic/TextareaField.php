<?php

namespace WPSettingsKit\Field\Basic;

use WPSettingsKit\Field\Base\AbstractField;

/**
 * Textarea field implementation
 *
 * Represents a multi-line text input field.
 */
class TextareaField extends AbstractField
{
    /**
     * @var int The number of rows in the textarea
     */
    private int $rows;

    /**
     * Constructor
     *
     * @param array $config Configuration array for the textarea field
     */
    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->rows = $config['rows'] ?? 5;
    }

    /**
     * Renders the textarea field as HTML
     *
     * @return string The rendered HTML markup
     */
    public function render(): string
    {
        $attributes = [
            'name' => $this->getKey(),
            'id' => $this->getKey(),
            'rows' => $this->rows,
            'class' => 'large-text',
            'required' => $this->isRequired() ? 'required' : null,
        ];

        $html = sprintf(
            '<textarea %s>%s</textarea>',
            $this->buildAttributeString($attributes),
            esc_textarea($this->getValue() ?? '')
        );

        return $this->decorator ? $this->decorator->decorate($html, $this) : $html;
    }

    /**
     * Sanitizes the textarea field value
     *
     * @return string The sanitized value
     */
    public function sanitize(): string
    {
        return sanitize_textarea_field($this->getValue());
    }
}