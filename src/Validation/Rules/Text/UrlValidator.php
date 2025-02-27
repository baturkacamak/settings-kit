<?php

namespace WPSettingsKit\Validation\Rules\Text;

use WPSettingsKit\Validation\Base\Interface\IValidationRule;

/**
 * Validates that a string value is a properly formatted URL.
 */
class UrlValidator implements IValidationRule
{
    /**
     * @var array<string> The allowed schemes (protocols).
     */
    private readonly array $allowedSchemes;

    /**
     * @var bool Whether to check if the host exists.
     */
    private readonly bool $checkHost;

    /**
     * Constructor for UrlValidator.
     *
     * @param array<string> $allowedSchemes Array of allowed schemes (e.g., ['http', 'https']).
     * @param bool $checkHost Whether to verify the host exists via DNS lookup.
     */
    public function __construct(array $allowedSchemes = ['http', 'https'], bool $checkHost = false)
    {
        $this->allowedSchemes = $allowedSchemes;
        $this->checkHost = $checkHost;
    }

    /**
     * Validates if the given value is a valid URL.
     *
     * @param mixed $value The value to validate (expected to be a string).
     * @return bool True if the value is a valid URL with allowed scheme, false otherwise.
     */
    public function validate(mixed $value): bool
    {
        if (!is_string($value) || empty($value)) {
            return false;
        }

        // Parse URL to get components
        $parsed = parse_url($value);

        // Check if URL is valid and has the required components
        if ($parsed === false || !isset($parsed['scheme']) || !isset($parsed['host'])) {
            return false;
        }

        // Verify the scheme is allowed
        if (!in_array($parsed['scheme'], $this->allowedSchemes, true)) {
            return false;
        }

        // Optional DNS check to verify host exists
        if ($this->checkHost && function_exists('checkdnsrr') && !checkdnsrr($parsed['host'], 'A')) {
            return false;
        }

        // Use filter_var as final validation
        $valid = filter_var($value, FILTER_VALIDATE_URL) !== false;

        return apply_filters('wp_settings_url_validator_result', $valid, $value, $this->allowedSchemes, $this->checkHost);
    }

    /**
     * Gets the error message for when validation fails.
     *
     * @return string The error message indicating invalid URL format.
     */
    public function getMessage(): string
    {
        $schemes = implode(', ', $this->allowedSchemes);
        return sprintf(
            __('Please enter a valid URL with one of these protocols: %s.', 'settings-manager'),
            $schemes
        );
    }

    /**
     * Gets the name of this validation rule.
     *
     * @return string The identifier for this validator.
     */
    public function getName(): string
    {
        return 'url';
    }

    /**
     * Gets the parameters used by this validator.
     *
     * @return array<string, mixed> An array containing validator parameters.
     */
    public function getParameters(): array
    {
        return [
            'allowedSchemes' => $this->allowedSchemes,
            'checkHost' => $this->checkHost
        ];
    }
}