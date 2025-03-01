<?php

namespace WPSettingsKit\Domain\Validation\Rules\Select;

use WPSettingsKit\Domain\Field\Enhancer\Attribute\ValidationRule;
use WPSettingsKit\Domain\Validation\Base\Interface\IValidationRule;

/**
 * Validates that a minimum number of options are selected for multi-select fields.
 */
#[ValidationRule(
    type: ['select', 'checkbox'],
    method: 'addMinSelectionsValidation',
    priority: 35
)]
class MinSelectionsValidationRule implements IValidationRule
{
    /**
     * @var int The minimum number of selections required
     */
    private int $minSelections;

    /**
     * @var string Custom error message
     */
    private readonly string $customMessage;

    /**
     * Constructor for MinSelectionsValidator.
     *
     * @param int $minSelections The minimum number of selections required
     * @param string|null $customMessage Optional custom error message
     */
    public function __construct(int $minSelections, ?string $customMessage = null)
    {
        $this->minSelections = max(1, $minSelections); // Ensure at least 1
        $this->customMessage = $customMessage ?? sprintf(
            __('Please select at least %d option(s).', 'wp-settings-kit'),
            $minSelections
        );
    }

    /**
     * Validates if the minimum number of options are selected.
     *
     * @param mixed $value The value to validate (expected to be an array for multi-select)
     * @return bool True if minimum selections are made, false otherwise
     */
    public function validate(mixed $value): bool
    {
        if (!is_array($value)) {
            return $this->minSelections <= 1; // Single selection meets requirement if min is 1
        }

        $count  = count($value);
        $result = $count >= $this->minSelections;

        return apply_filters('wp_settings_min_selections_validator_result', $result, $value, $this->minSelections);
    }

    /**
     * Gets the error message for when validation fails.
     *
     * @return string The error message indicating the minimum selections requirement
     */
    public function getMessage(): string
    {
        return apply_filters('wp_settings_min_selections_validator_message', $this->customMessage, $this->minSelections);
    }

    /**
     * Gets the name of this validation rule.
     *
     * @return string The identifier for this validator
     */
    public function getName(): string
    {
        return 'min_selections';
    }

    /**
     * Gets the parameters used by this validator.
     *
     * @return array<string, mixed> An array containing the minSelections parameter
     */
    public function getParameters(): array
    {
        return [
            'minSelections' => $this->minSelections,
            'customMessage' => $this->customMessage,
        ];
    }
}