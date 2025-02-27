<?php

namespace WPSettingsKit\Validation\Rules\Numeric;

use WPSettingsKit\Validation\Base\Interface\IValidationRule;

/**
 * Validates that a string contains only alphanumeric characters.
 */
class AlphanumericValidator implements IValidationRule
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
     * Constructor for AlphanumericValidator.
     *
     * @param bool $allowSpaces Whether to allow spaces in the string.
     * @param array<string> $additionalChars Additional allowed characters.
     */
    public function __construct(bool $allowSpaces = false, array $additionalChars = [])
    {
        $this->allowSpaces = $allowSpaces;
        $this->additionalChars = $additionalChars;

        // Build the pattern with additional chars if specified
        $additionalCharsPattern = '';
        if (!empty($additionalChars)) {
            $additionalCharsPattern = preg_quote(implode('', $additionalChars), '/');
        }

        $spacePattern = $allowSpaces ? '\s' : '';
        $this->pattern = '/^[a-zA-Z0-9' . $spacePattern . $additionalCharsPattern . ']+$/';
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
        $message = __('This field may only contain letters and numbers', 'settings-manager');

        if ($this->allowSpaces) {
            $message .= __(' and spaces', 'settings-manager');
        }

        if (!empty($this->additionalChars)) {
            $charsString = implode(' ', $this->additionalChars);
            $message .= sprintf(__(' and the characters: %s', 'settings-manager'), $charsString);
        }

        return $message . '.';
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
            'allowSpaces' => $this->allowSpaces,
            'additionalChars' => $this->additionalChars
        ];
    }
}