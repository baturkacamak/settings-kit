<?php

namespace WPSettingsKit\Decorator;

use WPSettingsKit\Field\Base\Interface\IField;

/**
 * Wraps field in a container with label and description
 */
class ContainerDecorator extends AbstractFieldDecorator {
    private string $containerClass;
    private string $labelClass;
    private string $descriptionClass;

    public function __construct(
        string $containerClass = 'field-container',
        string $labelClass = 'field-label',
        string $descriptionClass = 'field-description'
    ) {
        $this->containerClass = $containerClass;
        $this->labelClass = $labelClass;
        $this->descriptionClass = $descriptionClass;
    }

    public function decorate(string $html, IField $field): string {
        $output = sprintf('<div class="%s">', esc_attr($this->containerClass));

        // Add label if exists
        if ($label = $field->getLabel()) {
            $output .= sprintf(
                '<label class="%s" for="%s">%s</label>',
                esc_attr($this->labelClass),
                esc_attr($field->getKey()),
                esc_html($label)
            );
        }

        // Add field HTML
        $output .= $html;

        // Add description if exists
        if (method_exists($field, 'getDescription') && $description = $field->getDescription()) {
            $output .= sprintf(
                '<p class="%s">%s</p>',
                esc_attr($this->descriptionClass),
                esc_html($description)
            );
        }

        $output .= '</div>';

        return $output;
    }
}