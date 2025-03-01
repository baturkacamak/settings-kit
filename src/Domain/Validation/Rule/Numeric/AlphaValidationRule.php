<?php

namespace WPSettingsKit\Domain\Validation\Rules\Numeric;

use WPSettingsKit\Domain\Field\Enhancer\Attribute\ValidationRule;
use WPSettingsKit\Domain\Validation\Base\Interface\IValidationRule;

/**
 * Validates that a string contains only alphabetic characters.
 */
#[ValidationRule(
    type: ['text', 'password'],
    method: 'addAlphaValidation',
    priority: 25
)]
class AlphaValidationRule implements IValidationRule
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
     * @var string Custom error message
     */
    private readonly string $customMessage;

    /**
     * Constructor for AlphaValidator.
     *
     * @param bool $allowSpaces Whether to allow spaces in the string.
     * @param string|null $customMessage Optional custom error message.
     */
    public function __construct(bool $allowSpaces = false, ?string $customMessage = null)
    {
        $this->allowSpaces = $allowSpaces;
        $this->pattern     = $allowSpaces ? '/^[a-zA-Z\s]+$/u' : '/^[a-zA-Z]+$/u';

        if ($customMessage === null) {
            $this->customMessage = $allowSpaces
                ? __('This field may only contain letters and spaces.', 'wp-settings-kit')
                : __('This field may only contain letters.', 'wp-settings-kit');
        } else {
            $this->customMessage = $customMessage;
        }
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
        return apply_filters('wp_settings_alpha_validator_message', $this->customMessage, $this->allowSpaces);
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
            'allowSpaces'   => $this->allowSpaces,
            'pattern'       => $this->pattern,
            'customMessage' => $this->customMessage,
        ];
    }
}