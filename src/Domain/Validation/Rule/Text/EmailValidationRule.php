<?php

namespace WPSettingsKit\Domain\Validation\Rules\Text;

use WPSettingsKit\Domain\Field\Enhancer\Attribute\ValidationRule;
use WPSettingsKit\Domain\Validation\Base\Interface\IValidationRule;

/**
 * Email validation rule.
 *
 * Validates that a string is a properly formatted email address.
 */
#[ValidationRule(
    type: ['text', 'email'],
    method: 'addEmailValidation',
    priority: 10
)]
class EmailValidationRule implements IValidationRule
{
    /**
     * @var bool Whether to check for MX records
     */
    private bool $checkDns;

    /**
     * @var string Custom error message
     */
    private string $customMessage;

    /**
     * Constructor.
     *
     * @param bool $checkDns Whether to verify domain has valid MX records
     * @param string|null $customMessage Optional custom error message
     */
    public function __construct(bool $checkDns = false, ?string $customMessage = null)
    {
        $this->checkDns = $checkDns;

        if ($customMessage === null) {
            $this->customMessage = $checkDns
                ? __('Please enter a valid email address with a working domain.', 'wp-settings-kit')
                : __('Please enter a valid email address.', 'wp-settings-kit');
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

        // Basic format check using filter_var
        $valid = filter_var($value, FILTER_VALIDATE_EMAIL) !== false;

        // Optional DNS check for MX records
        if ($valid && $this->checkDns) {
            $domain = substr(strrchr($value, '@'), 1);
            $valid  = checkdnsrr($domain, 'MX');
        }

        return apply_filters('wp_settings_email_validator_result', $valid, $value, $this->checkDns);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage(): string
    {
        return apply_filters('wp_settings_email_validator_message', $this->customMessage);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'email';
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(): array
    {
        return [
            'checkDns'      => $this->checkDns,
            'customMessage' => $this->customMessage,
        ];
    }
}