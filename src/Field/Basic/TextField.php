<?php

namespace WPSettingsKit\Field\Basic;

use WPSettingsKit\Field\Base\AbstractField;
use WPSettingsKit\Field\Base\Interface\IFieldRenderer;
use WPSettingsKit\Field\Renderer\InputRenderer;

/**
 * Enhanced text input field implementation.
 */
class TextField extends AbstractField
{
    public function __construct(
        array            $config,
        protected string $placeholder = '',
        protected int    $maxLength = 0,
        protected int    $minLength = 0,
        protected string $pattern = '',
        protected string $inputMode = 'text',
        protected string $autocomplete = '',
        protected bool   $readonly = false,
        protected bool   $disabled = false,
        protected int    $size = 0,
        protected string $prefix = '',
        protected string $suffix = '',
        protected string $cssClass = '',
        protected string $inputType = 'text'
    )
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

        $this->placeholder  = $config['placeholder'] ?? '';
        $this->maxLength    = $config['max_length'] ?? 0;
        $this->minLength    = $config['min_length'] ?? 0;
        $this->pattern      = $config['pattern'] ?? '';
        $this->inputMode    = $config['input_mode'] ?? 'text';
        $this->autocomplete = $config['autocomplete'] ?? '';
        $this->readonly     = $config['readonly'] ?? false;
        $this->disabled     = $config['disabled'] ?? false;
        $this->size         = $config['size'] ?? 0;
        $this->prefix       = $config['prefix'] ?? '';
        $this->suffix       = $config['suffix'] ?? '';
        $this->cssClass     = $config['css_class'] ?? '';
        $this->inputType    = $config['input_type'] ?? 'text';
    }

    /**
     * Renders the text field as HTML.
     */
    public function render(): string
    {
        $classes = ['regular-text'];
        if ($this->cssClass) {
            $classes[] = $this->cssClass;
        }

        $attributes = [
            'type'         => $this->inputType,
            'name'         => $this->getKey(),
            'id'           => $this->getKey(),
            'value'        => htmlspecialchars($this->getValue() ?? ''),
            'class'        => implode(' ', $classes),
            'placeholder'  => $this->placeholder ? htmlspecialchars($this->placeholder) : null,
            'maxlength'    => $this->maxLength > 0 ? $this->maxLength : null,
            'minlength'    => $this->minLength > 0 ? $this->minLength : null,
            'pattern'      => $this->pattern ?: null,
            'inputmode'    => $this->inputMode ?: null,
            'autocomplete' => $this->autocomplete ?: null,
            'readonly'     => $this->readonly ? 'readonly' : null,
            'disabled'     => $this->disabled ? 'disabled' : null,
            'size'         => $this->size > 0 ? $this->size : null,
            'required'     => $this->isRequired() ? 'required' : null,
        ];

        // PHP 8.1+ null safe operator kullanımı ve string interpolation
        $html = '';

        // Add prefix if present
        if ($this->prefix) {
            $html .= "<span class=\"field-prefix\">{$this->prefix}</span>";
        }

        // Add the input element
        $html .= $this->renderer->render($this, $attributes);

        // Add suffix if present
        if ($this->suffix) {
            $html .= "<span class=\"field-suffix\">{$this->suffix}</span>";
        }

        return $html;
    }

    /**
     * Sanitizes the text field value.
     */
    public function sanitize(): string
    {
        $value = $this->getValue();

        if (is_null($value)) {
            return '';
        }

        return match ($this->inputType) {
            'email' => sanitize_email($value),
            'url' => esc_url_raw($value),
            'number' => is_numeric($value) ? (string)floatval($value) : '',
            default => sanitize_text_field($value),
        };
    }

    /**
     * Provides the default renderer for the text field.
     */
    protected function getDefaultRenderer(): IFieldRenderer
    {
        return new InputRenderer();
    }
}