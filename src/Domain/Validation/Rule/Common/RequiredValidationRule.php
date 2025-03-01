<?php

namespace WPSettingsKit\Domain\Validation\Rules\Common;

use WPSettingsKit\Domain\Field\Enhancer\Attribute\ValidationRule;
use WPSettingsKit\Domain\Validation\Base\Interface\IValidationRule;

/**
 * Required validation rule.
 *
 * Validates that a value is not empty.
 */
#[ValidationRule(
    type: 'all',
    method: 'addRequiredValidation',
    priority: 5
)]
class RequiredValidationRule implements IValidationRule
{
    /**
     * @var string Custom error message
     */
    private string $customMessage;

    /**
     * Constructor.
     *
     * @param string|null $customMessage Optional custom error message
     */
    public function __construct(?string $customMessage = null)
    {
        $this->customMessage = $customMessage ?? __('This field is required.', 'wp-settings-kit');
    }

    /**
     * {@inheritdoc}
     */
    public function validate(mixed $value): bool
    {
        if (is_string($value)) {
            $result = trim($value) !== '';
        } else if (is_array($value)) {
            $result = !empty($value);
        } else {
            $result = $value !== null && $value !== false;
        }

        return apply_filters('wp_settings_required_validator_result', $result, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage(): string
    {
        return apply_filters('wp_settings_required_validator_message', $this->customMessage);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'required';
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(): array
    {
        return [
            'customMessage' => $this->customMessage,
        ];
    }
}