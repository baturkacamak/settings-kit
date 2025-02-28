<?php

namespace WPSettingsKit\Validation\Rules\Common;

use WPSettingsKit\Attribute\ValidationRule;
use WPSettingsKit\Validation\Base\Interface\IValidationRule;

/**
 * Validates that a string contains only alphanumeric characters.
 */
#[ValidationRule(
    type: ['text', 'password'],
    method: 'addAlphanumericValidation',
    priority: 25
)]
class AlphanumericValidationRule implements IValidationRule
{
    /**
     * @var bool Whether to allow spaces.
     */
    private readonly bool $allowSpaces;

    /**
     * @var array<string> Additional allowed characters.
     */
    private readonly array $additionalChars;

    /**
     * @var string The pattern to validate against.
     */
    private readonly string $pattern;

    /**
     * @var string Custom error message
     */
    private readonly string $customMessage;

    /**
     * Constructor for AlphanumericValidator.
     *
     * @param bool $allowSpaces Whether to allow spaces in the string.
     * @param array<string> $additionalChars Additional allowed characters.
     * @param string|null $customMessage Optional custom error message.
     */
    public function __construct(bool $allowSpaces = false, array $additionalChars = [], ?string $customMessage = null)
    {
        $this->allowSpaces     = $allowSpaces;
        $this->additionalChars = $additionalChars;

        // Build the pattern with additional chars if specified
        $additionalCharsPattern = '';
        if (!empty($additionalChars)) {
            $additionalCharsPattern = preg_quote(implode('', $additionalChars), '/');
        }

        $spacePattern  = $allowSpaces ? '\s' : '';
        $this->pattern = '/^[a-zA-Z0-9' . $spacePattern . $additionalCharsPattern . ']+$/u';

        // Generate custom message
        if ($customMessage === null) {
            $message = __('This field may only contain letters and numbers', 'wp-settings-kit');

            if ($this->allowSpaces) {
                $message .= __(' and spaces', 'wp-settings-kit');
            }

            if (!empty($this->additionalChars)) {
                $charsString = implode(' ', $this->additionalChars);
                $message     .= sprintf(__(' and the characters: %s', 'wp-settings-kit'), $charsString);
            }

            $this->customMessage = $message . '.';
        } else {
            $this->customMessage = $customMessage;
        }
    }

    /**
     * Validates if the given value contains only alphanumeric characters.
     *
     * @param mixed $value The value to validate (expected to be a string).
     * @return bool True if the value contains only allowed characters, false otherwise.
     */
    public function validate(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        $result = preg_match($this->pattern, $value) === 1;
        return apply_filters(
            'wp_settings_alphanumeric_validator_result',
            $result,
            $value,
            $this->allowSpaces,
            $this->additionalChars
        );
    }

    /**
     * Gets the error message for when validation fails.
     *
     * @return string The error message.
     */
    public function getMessage(): string
    {
        return apply_filters(
            'wp_settings_alphanumeric_validator_message',
            $this->customMessage,
            $this->allowSpaces,
            $this->additionalChars
        );
    }

    /**
     * Gets the name of this validation rule.
     *
     * @return string The identifier for this validator.
     */
    public function getName(): string
    {
        return 'alphanumeric';
    }

    /**
     * Gets the parameters used by this validator.
     *
     * @return array<string, mixed> An array containing the validator parameters.
     */
    public function getParameters(): array
    {
        return [
            'allowSpaces'     => $this->allowSpaces,
            'additionalChars' => $this->additionalChars,
            'pattern'         => $this->pattern,
            'customMessage'   => $this->customMessage,
        ];
    }
}