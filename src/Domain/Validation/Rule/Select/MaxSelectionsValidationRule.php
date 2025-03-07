<?php

namespace WPSettingsKit\Domain\Validation\Rules\Select;

use WPSettingsKit\Domain\Field\Enhancer\Attribute\ValidationRule;
use WPSettingsKit\Domain\Validation\Base\Interface\IValidationRule;

/**
 * Validates that the number of selected options does not exceed a maximum limit.
 */
#[ValidationRule(
    type: ['select', 'checkbox'],
    method: 'addMaxSelectionsValidation',
    priority: 40
)]
class MaxSelectionsValidationRule implements IValidationRule
{
    /**
     * @var int The maximum number of selections allowed
     */
    private int $maxSelections;

    /**
     * @var string Custom error message
     */
    private readonly string $customMessage;

    /**
     * Constructor for MaxSelectionsValidator.
     *
     * @param int $maxSelections The maximum number of selections allowed
     * @param string|null $customMessage Optional custom error message
     */
    public function __construct(int $maxSelections, ?string $customMessage = null)
    {
        $this->maxSelections = $maxSelections;
        $this->customMessage = $customMessage ?? sprintf(
            __('Please select no more than %d option(s).', 'wp-settings-kit'),
            $maxSelections
        );
    }

    /**
     * Validates if the selection count does not exceed the maximum limit.
     *
     * @param mixed $value The value to validate (expected to be an array for multi-select)
     * @return bool True if selection count is within limit, false otherwise
     */
    public function validate(mixed $value): bool
    {
        if (!is_array($value)) {
            return true; // Single selection always meets max requirement
        }

        $count  = count($value);
        $result = $count <= $this->maxSelections;

        return apply_filters('wp_settings_max_selections_validator_result', $result, $value, $this->maxSelections);
    }

    /**
     * Gets the error message for when validation fails.
     *
     * @return string The error message indicating the maximum selections limit
     */
    public function getMessage(): string
    {
        return apply_filters('wp_settings_max_selections_validator_message', $this->customMessage, $this->maxSelections);
    }

    /**
     * Gets the name of this validation rule.
     *
     * @return string The identifier for this validator
     */
    public function getName(): string
    {
        return 'max_selections';
    }

    /**
     * Gets the parameters used by this validator.
     *
     * @return array<string, mixed> An array containing the maxSelections parameter
     */
    public function getParameters(): array
    {
        return [
            'maxSelections' => $this->maxSelections,
            'customMessage' => $this->customMessage,
        ];
    }
}