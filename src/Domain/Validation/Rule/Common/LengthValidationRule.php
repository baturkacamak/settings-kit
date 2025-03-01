<?php

namespace WPSettingsKit\Domain\Validation\Rules\Common;

use WPSettingsKit\Domain\Field\Enhancer\Attribute\ValidationRule;
use WPSettingsKit\Domain\Validation\Base\Interface\IValidationRule;

/**
 * Length validation rule.
 *
 * Validates that a string's length is within specified limits.
 */
#[ValidationRule(
    type: ['text', 'textarea', 'password'],
    method: 'addLengthValidation',
    priority: 10
)]
class LengthValidationRule implements IValidationRule
{
    /**
     * @var int|null Minimum length requirement
     */
    private ?int $minLength;

    /**
     * @var int|null Maximum length requirement
     */
    private ?int $maxLength;

    /**
     * @var string Custom error message
     */
    private string $customMessage;

    /**
     * Constructor.
     *
     * @param int|null $minLength Minimum length (null for no minimum)
     * @param int|null $maxLength Maximum length (null for no maximum)
     * @param string|null $customMessage Optional custom error message
     */
    public function __construct(?int $minLength = null, ?int $maxLength = null, ?string $customMessage = null)
    {
        $this->minLength = $minLength;
        $this->maxLength = $maxLength;
        $this->customMessage = $customMessage ?? $this->generateDefaultMessage();
    }

    /**
     * {@inheritdoc}
     */
    public function validate(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        $length = mb_strlen($value);

        if ($this->minLength !== null && $length < $this->minLength) {
            return false;
        }

        if ($this->maxLength !== null && $length > $this->maxLength) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage(): string
    {
        return apply_filters('wp_settings_length_validator_message', $this->customMessage, $this->minLength, $this->maxLength);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'length';
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(): array
    {
        return [
            'minLength' => $this->minLength,
            'maxLength' => $this->maxLength,
            'customMessage' => $this->customMessage
        ];
    }

    /**
     * Generates a default error message based on constraints.
     *
     * @return string Default error message
     */
    private function generateDefaultMessage(): string
    {
        if ($this->minLength !== null && $this->maxLength !== null) {
            return sprintf(
                __('This field must be between %d and %d characters long.', 'wp-settings-kit'),
                $this->minLength,
                $this->maxLength
            );
        } else if ($this->minLength !== null) {
            return sprintf(
                __('This field must be at least %d characters long.', 'wp-settings-kit'),
                $this->minLength
            );
        } else if ($this->maxLength !== null) {
            return sprintf(
                __('This field cannot be longer than %d characters.', 'wp-settings-kit'),
                $this->maxLength
            );
        }

        return __('Invalid field length.', 'wp-settings-kit');
    }
}