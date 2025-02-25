<?php

namespace WPSettingsKit\Validation;

use WPSettingsKit\Validation\Interface\IValidationRule;

/**
 * Validates that a value is not empty or null, marking it as required.
 */
class RequiredValidator implements IValidationRule
{
    /**
     * Validates if the given value is non-empty and non-null.
     *
     * @param mixed $value The value to validate.
     * @return bool True if the value is non-empty (string) or non-null/non-empty array, false otherwise.
     */
    public function validate(mixed $value): bool
    {
        if (is_string($value)) {
            $result = trim($value) !== '';
        } else {
            $result = $value !== null && $value !== [];
        }
        return apply_filters('wp_settings_required_validator_result', $result, $value);
    }

    /**
     * Gets the error message for when validation fails.
     *
     * @return string The error message indicating the field is required.
     */
    public function getMessage(): string
    {
        $message = __('This field is required.', 'settings-manager');
        return apply_filters('wp_settings_required_validator_message', $message);
    }

    /**
     * Gets the name of this validation rule.
     *
     * @return string The identifier for this validator.
     */
    public function getName(): string
    {
        return 'required';
    }

    /**
     * Gets the parameters used by this validator.
     *
     * @return array<string, mixed> An empty array since this validator has no parameters.
     */
    public function getParameters(): array
    {
        return [];
    }
}