<?php

namespace WPSettingsKit\Validation;

use WPSettingsKit\Validation\Interface\IValidationRule;

/**
 * Validates that a string contains only alphabetic characters.
 */
class AlphaValidator implements IValidationRule
{
    /**
     * @var bool Whether to allow spaces.
     */
    private readonly bool $allowSpaces;

    /**
     * @var string The allowed characters pattern.
     */
    private readonly string $pattern;

    /**
     * Constructor for AlphaValidator.
     *
     * @param bool $allowSpaces Whether to allow spaces in the string.
     */
    public function __construct(bool $allowSpaces = false)
    {
        $this->allowSpaces = $allowSpaces;
        $this->pattern = $allowSpaces ? '/^[a-zA-Z\s]+$/' : '/^[a-zA-Z]+$/';
    }

    /**
     * Validates if the given value contains only alphabetic characters.
     *
     * @param mixed $value The value to validate (expected to be a string).
     * @return bool True if the value contains only alphabetic characters, false otherwise.
     */
    public function validate(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        $result = preg_match($this->pattern, $value) === 1;
        return apply_filters('wp_settings_alpha_validator_result', $result, $value, $this->allowSpaces);
    }

    /**
     * Gets the error message for when validation fails.
     *
     * @return string The error message.
     */
    public function getMessage(): string
    {
        return $this->allowSpaces
            ? __('This field may only contain letters and spaces.', 'settings-manager')
            : __('This field may only contain letters.', 'settings-manager');
    }

    /**
     * Gets the name of this validation rule.
     *
     * @return string The identifier for this validator.
     */
    public function getName(): string
    {
        return 'alpha';
    }

    /**
     * Gets the parameters used by this validator.
     *
     * @return array<string, mixed> An array containing the validator parameters.
     */
    public function getParameters(): array
    {
        return [
            'allowSpaces' => $this->allowSpaces
        ];
    }
}