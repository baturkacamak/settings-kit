<?php

namespace WPSettingsKit\Domain\Validation\Rules\Select;

use WPSettingsKit\Domain\Field\Enhancer\Attribute\ValidationRule;
use WPSettingsKit\Domain\Validation\Base\Interface\IValidationRule;

/**
 * Validates that a selection matches a specific pattern or format.
 */
#[ValidationRule(
    type: ['select', 'radio'],
    method: 'addPatternSelectionValidation',
    priority: 45
)]
class PatternSelectionValidationRule implements IValidationRule
{
    /**
     * @var string The regex pattern to match against
     */
    private string $pattern;

    /**
     * @var string Description of the pattern for error messages
     */
    private string $patternDescription;

    /**
     * @var string Custom error message
     */
    private readonly string $customMessage;

    /**
     * Constructor for PatternSelectionValidator.
     *
     * @param string $pattern The regex pattern for validation
     * @param string $patternDescription Human-readable description of the pattern
     * @param string|null $customMessage Optional custom error message
     */
    public function __construct(string $pattern, string $patternDescription = '', ?string $customMessage = null)
    {
        $this->pattern            = $pattern;
        $this->patternDescription = $patternDescription;

        if ($customMessage === null) {
            if (!empty($patternDescription)) {
                $this->customMessage = sprintf(
                    __('The selected option must match the format: %s', 'wp-settings-kit'),
                    $patternDescription
                );
            } else {
                $this->customMessage = __('The selected option is in an invalid format.', 'wp-settings-kit');
            }
        } else {
            $this->customMessage = $customMessage;
        }
    }

    /**
     * Validates if the selection matches the required pattern.
     *
     * @param mixed $value The value to validate
     * @return bool True if selection matches pattern, false otherwise
     */
    public function validate(mixed $value): bool
    {
        if (is_array($value)) {
            // For multi-select, all items must match the pattern
            foreach ($value as $item) {
                if (!is_string($item) || preg_match($this->pattern, $item) !== 1) {
                    return false;
                }
            }
            return true;
        }

        // For single select
        if (!is_string($value)) {
            return false;
        }

        $result = preg_match($this->pattern, $value) === 1;
        return apply_filters('wp_settings_pattern_selection_validator_result', $result, $value, $this->pattern);
    }

    /**
     * Gets the error message for when validation fails.
     *
     * @return string The error message indicating the selection format is invalid
     */
    public function getMessage(): string
    {
        return apply_filters(
            'wp_settings_pattern_selection_validator_message',
            $this->customMessage,
            $this->pattern,
            $this->patternDescription
        );
    }

    /**
     * Gets the name of this validation rule.
     *
     * @return string The identifier for this validator
     */
    public function getName(): string
    {
        return 'pattern_selection';
    }

    /**
     * Gets the parameters used by this validator.
     *
     * @return array<string, mixed> An array containing validation parameters
     */
    public function getParameters(): array
    {
        return [
            'pattern'            => $this->pattern,
            'patternDescription' => $this->patternDescription,
            'customMessage'      => $this->customMessage,
        ];
    }
}