<?php

namespace WPSettingsKit\Domain\Validation\Rules\Text;

use WPSettingsKit\Domain\Field\Enhancer\Attribute\ValidationRule;
use WPSettingsKit\Domain\Validation\Base\Interface\IValidationRule;

/**
 * URL validation rule.
 *
 * Validates that a string is a properly formatted URL.
 */
#[ValidationRule(
    type: ['text', 'url'],
    method: 'addUrlValidation',
    priority: 10
)]
class UrlValidationRule implements IValidationRule
{
    /**
     * @var array<string> Allowed URL schemes/protocols
     */
    private array $allowedSchemes;

    /**
     * @var bool Whether to check if the host exists via DNS lookup
     */
    private bool $checkHost;

    /**
     * @var string Custom error message
     */
    private string $customMessage;

    /**
     * Constructor.
     *
     * @param array<string>|null $allowedSchemes Allowed schemes (null for all)
     * @param bool $checkHost Whether to verify the host exists
     * @param string|null $customMessage Optional custom error message
     */
    public function __construct(?array $allowedSchemes = null, bool $checkHost = false, ?string $customMessage = null)
    {
        $this->allowedSchemes = $allowedSchemes ?? ['http', 'https'];
        $this->checkHost      = $checkHost;

        if ($customMessage === null) {
            $schemes             = implode(', ', $this->allowedSchemes);
            $this->customMessage = sprintf(
                __('Please enter a valid URL with one of these protocols: %s.', 'wp-settings-kit'),
                $schemes
            );
        } else {
            $this->customMessage = $customMessage;
        }
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function getMessage(): string
    {
        return apply_filters('wp_settings_url_validator_message', $this->customMessage);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'url';
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(): array
    {
        return [
            'allowedSchemes' => $this->allowedSchemes,
            'checkHost'      => $this->checkHost,
            'customMessage'  => $this->customMessage,
        ];
    }
}