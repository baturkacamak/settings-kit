<?php

namespace WPSettingsKit\Validation;

use WPSettingsKit\Validation\Interface\IValidationRule;

/**
 * Validates that a string value meets or exceeds a specified minimum length.
 */
class MinLengthValidator implements IValidationRule
{
    /**
     * @var int The minimum required length for the value.
     */
    private readonly int $minLength;

    /**
     * Constructor for MinLengthValidator.
     *
     * @param int $minLength The minimum length required for the validated string.
     */
    public function __construct(int $minLength)
    {
        $this->minLength = $minLength;
    }

    /**
     * Validates if the given value's length meets the minimum requirement.
     *
     * @param mixed $value The value to validate (expected to be a string).
     * @return bool True if the value is a string and its length is greater than or equal to minLength, false otherwise.
     */
    public function validate(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }
        $result = strlen($value) >= $this->minLength;
        return apply_filters('wp_settings_min_length_validator_result', $result, $value, $this->minLength);
    }

    /**
     * Gets the error message for when validation fails.
     *
     * @return string The error message indicating the minimum length constraint.
     */
    public function getMessage(): string
    {
        return sprintf(
            __('This field must be at least %d characters long.', 'settings-manager'),
            $this->minLength
        );
    }

    /**
     * Gets the name of this validation rule.
     *
     * @return string The identifier for this validator.
     */
    public function getName(): string
    {
        return 'min_length';
    }

    /**
     * Gets the parameters used by this validator.
     *
     * @return array<string, mixed> An array containing the minLength parameter.
     */
    public function getParameters(): array
    {
        return ['minLength' => $this->minLength];
    }
}