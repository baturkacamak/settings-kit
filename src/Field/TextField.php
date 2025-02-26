<?php

namespace WPSettingsKit\Field;

use WPSettingsKit\Field\Interface\IFieldRenderer;

/**
 * Text input field implementation.
 */
class TextField extends AbstractField
{
    /**
     * @var string The placeholder text for the field.
     */
    protected string $placeholder;

    /**
     * @var int The maximum length of the input.
     */
    protected int $maxLength;

    /**
     * Constructor for TextField.
     *
     * @param array<string, mixed> $config Configuration array for the text field.
     */
    public function __construct(array $config)
    {
        parent::__construct(
            $config['key'],
            $config['label'],
            $config['value'] ?? null,
            $config['required'] ?? false,
            $config['description'] ?? '',
            $config['validation_rules'] ?? [],
            $config['dependencies'] ?? [],
            $config['transformer'] ?? null,
            $config['decorator'] ?? null,
            $config['event_dispatcher'] ?? null,
            $config['renderer'] ?? null
        );
        $this->placeholder = $config['placeholder'] ?? '';
        $this->maxLength   = $config['max_length'] ?? 0;
    }

    /**
     * Renders the text field as HTML.
     *
     * @return string The rendered HTML markup.
     */
    public function render(): string
    {
        $attributes = [
            'type'        => 'text',
            'name'        => $this->getKey(),
            'id'          => $this->getKey(),
            'value'       => htmlspecialchars($this->getValue() ?? ''),
            'class'       => 'regular-text',
            'placeholder' => $this->placeholder ? htmlspecialchars($this->placeholder) : null,
            'maxlength'   => $this->maxLength > 0 ? $this->maxLength : null,
            'required'    => $this->isRequired() ? 'required' : null,
        ];

        return $this->renderer->render($this, $attributes);
    }

    /**
     * Sanitizes the text field value.
     *
     * @return string The sanitized value.
     */
    public function sanitize(): string
    {
        return sanitize_text_field($this->getValue());
    }

    /**
     * Provides the default renderer for the text field.
     *
     * @return IFieldRenderer The default renderer instance.
     */
    protected function getDefaultRenderer(): IFieldRenderer
    {
        return new InputRenderer();
    }
}