<?php

namespace WPSettingsKit\Validation;

use WPSettingsKit\Validation\Interface\IValidationRule;

/**
 * Validates that a string value is a properly formatted email address.
 */
class EmailValidator implements IValidationRule
{
    /**
     * @var bool Whether to check for MX records (requires DNS lookup).
     */
    private readonly bool $checkDns;

    /**
     * Constructor for EmailValidator.
     *
     * @param bool $checkDns Whether to verify domain has valid MX records.
     */
    public function __construct(bool $checkDns = false)
    {
        $this->checkDns = $checkDns;
    }

    /**
     * Validates if the given value is a valid email address.
     *
     * @param mixed $value The value to validate (expected to be a string).
     * @return bool True if the value is a valid email address, false otherwise.
     */
    public function validate(mixed $value): bool
    {
        if (!is_string($value) || empty($value)) {
            return false;
        }

        // Basic format check using filter_var
        $valid = filter_var($value, FILTER_VALIDATE_EMAIL) !== false;

        // Optional DNS check for MX records
        if ($valid && $this->checkDns) {
            $domain = substr(strrchr($value, '@'), 1);
            $valid = checkdnsrr($domain, 'MX');
        }

        return apply_filters('wp_settings_email_validator_result', $valid, $value, $this->checkDns);
    }

    /**
     * Gets the error message for when validation fails.
     *
     * @return string The error message indicating invalid email format.
     */
    public function getMessage(): string
    {
        return $this->checkDns
            ? __('Please enter a valid email address with a working domain.', 'settings-manager')
            : __('Please enter a valid email address.', 'settings-manager');
    }

    /**
     * Gets the name of this validation rule.
     *
     * @return string The identifier for this validator.
     */
    public function getName(): string
    {
        return 'email';
    }

    /**
     * Gets the parameters used by this validator.
     *
     * @return array<string, mixed> An array containing the checkDns parameter.
     */
    public function getParameters(): array
    {
        return ['checkDns' => $this->checkDns];
    }
}