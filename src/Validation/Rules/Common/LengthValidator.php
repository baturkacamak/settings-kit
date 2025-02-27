<?php

namespace WPSettingsKit\Validation\Rules\Common;

use WPSettingsKit\Validation\Base\Interface\IValidationRule;

/**
 * Validates that a string value does not exceed a specified maximum length.
 */
class LengthValidator implements IValidationRule
{
    /**
     * @var int The maximum allowed length for the value.
     */
    private int $maxLength;

    /**
     * Constructor for LengthValidator.
     *
     * @param int $maxLength The maximum length allowed for the validated string.
     */
    public function __construct(int $maxLength)
    {
        $this->maxLength = $maxLength;
    }

    /**
     * Validates if the given value's length is within the maximum limit.
     *
     * @param mixed $value The value to validate (expected to be a string).
     * @return bool True if the value is a string and its length is less than or equal to maxLength, false otherwise.
     */
    public function validate(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }
        $result = strlen($value) <= $this->maxLength;
        return apply_filters('wp_settings_length_validator_result', $result, $value, $this->maxLength);
    }

    /**
     * Gets the error message for when validation fails.
     *
     * @return string The error message indicating the maximum length constraint.
     */
    public function getMessage(): string
    {
        $message = sprintf(
            __('This field cannot be longer than %d characters.', 'settings-manager'),
            $this->maxLength
        );
        return apply_filters('wp_settings_length_validator_message', $message, $this->maxLength);
    }

    /**
     * Gets the name of this validation rule.
     *
     * @return string The identifier for this validator (e.g., for logging or debugging).
     */
    public function getName(): string
    {
        return 'length';
    }

    /**
     * Gets the parameters used by this validator.
     *
     * @return array<string, mixed> An array containing the maxLength parameter.
     */
    public function getParameters(): array
    {
        return ['maxLength' => $this->maxLength];
    }
}