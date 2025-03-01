<?php

namespace WPSettingsKit\Enhancer;

use WPSettingsKit\Domain\Field\Base\Interface\IField;

/**
 * Adds error message display capability
 */
class ValidationEnhancer extends AbstractFieldEnhancer {
    private string $errorClass;

    public function __construct(string $errorClass = 'field-error') {
        $this->errorClass = $errorClass;
    }

    public function decorate(string $html, IField $field): string {
        $output = $html;

        // Add error container
        $output .= sprintf(
            '<div class="%s" data-field="%s" style="display: none;"></div>',
            esc_attr($this->errorClass),
            esc_attr($field->getKey())
        );

        return $output;
    }
}
