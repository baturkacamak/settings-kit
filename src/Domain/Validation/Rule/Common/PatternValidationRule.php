<?php

namespace WPSettingsKit\Domain\Validation\Rules\Common;

use WPSettingsKit\Domain\Field\Enhancer\Attribute\ValidationRule;
use WPSettingsKit\Domain\Validation\Base\Interface\IValidationRule;

/**
 * Pattern validation rule.
 *
 * Validates that a string matches a regular expression pattern.
 */
#[ValidationRule(
    type: ['text', 'textarea', 'password'],
    method: 'addPatternValidation',
    priority: 15
)]
class PatternValidationRule implements IValidationRule
{
    /**
     * @var string Regular expression pattern
     */
    private string $pattern;

    /**
     * @var string|null Human-readable pattern description
     */
    private ?string $description;

    /**
     * @var string Custom error message
     */
    private string $customMessage;

    /**
     * Constructor.
     *
     * @param string $pattern Regular expression pattern
     * @param string|null $description Human-readable pattern description
     * @param string|null $customMessage Optional custom error message
     */
    public function __construct(string $pattern, ?string $description = null, ?string $customMessage = null)
    {
        $this->pattern = $pattern;
        $this->description = $description;

        if ($customMessage === null) {
            if ($description !== null) {
                $this->customMessage = sprintf(
                    __('This field must match the format: %s', 'wp-settings-kit'),
                    $description
                );
            } else {
                $this->customMessage = __('This field does not match the required format.', 'wp-settings-kit');
            }
        } else {
            $this->customMessage = $customMessage;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validate(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        $result = preg_match($this->pattern, $value) === 1;
        return apply_filters('wp_settings_pattern_validator_result', $result, $value, $this->pattern);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage(): string
    {
        return apply_filters('wp_settings_pattern_validator_message', $this->customMessage);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'pattern';
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(): array
    {
        return [
            'pattern' => $this->pattern,
            'description' => $this->description,
            'customMessage' => $this->customMessage
        ];
    }
}