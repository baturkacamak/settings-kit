<?php

namespace WPSettingsKit\Validation;

use WPSettingsKit\Validation\Interface\IValidationRule;

/**
 * Validates that a string value matches a specific regex pattern.
 */
class PatternValidator implements IValidationRule
{
    /**
     * @var string The regex pattern to validate against.
     */
    private readonly string $pattern;

    /**
     * @var string Custom error message.
     */
    private readonly string $customMessage;

    /**
     * Constructor for PatternValidator.
     *
     * @param string $pattern The regex pattern to match against.
     * @param string $customMessage Optional custom error message.
     */
    public function __construct(string $pattern, string $customMessage = '')
    {
        $this->pattern = $pattern;
        $this->customMessage = $customMessage;
    }

    /**
     * Validates if the given value matches the pattern.
     *
     * @param mixed $value The value to validate (expected to be a string).
     * @return bool True if the value matches the pattern, false otherwise.
     */
    public function validate(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        $result = preg_match($this->pattern, $value) === 1;
        return apply_filters('wp_settings_pattern_validator_result', $result, $value, $this->pattern);
    }

    /**
     * Gets the error message for when validation fails.
     *
     * @return string The error message indicating the pattern mismatch.
     */
    public function getMessage(): string
    {
        if (!empty($this->customMessage)) {
            return $this->customMessage;
        }

        return __('This field does not match the required format.', 'settings-manager');
    }

    /**
     * Gets the name of this validation rule.
     *
     * @return string The identifier for this validator.
     */
    public function getName(): string
    {
        return 'pattern';
    }

    /**
     * Gets the parameters used by this validator.
     *
     * @return array<string, mixed> An array containing the pattern parameter.
     */
    public function getParameters(): array
    {
        return [
            'pattern' => $this->pattern,
            'customMessage' => $this->customMessage
        ];
    }
}