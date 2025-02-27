<?php

namespace WPSettingsKit\Validation;

use WPSettingsKit\Validation\Interface\IValidationRule;

/**
 * Validates that a numeric value falls within a specified range.
 */
class NumberRangeValidator implements IValidationRule
{
    /**
     * @var float|null The minimum allowed value, or null if no minimum.
     */
    private readonly ?float $min;

    /**
     * @var float|null The maximum allowed value, or null if no maximum.
     */
    private readonly ?float $max;

    /**
     * Constructor for NumberRangeValidator.
     *
     * @param float|null $min The minimum allowed value, or null for no minimum.
     * @param float|null $max The maximum allowed value, or null for no maximum.
     */
    public function __construct(?float $min = null, ?float $max = null)
    {
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * Validates if the given value is within the specified range.
     *
     * @param mixed $value The value to validate (expected to be numeric).
     * @return bool True if the value is within range, false otherwise.
     */
    public function validate(mixed $value): bool
    {
        // Convert to numeric if possible, or return false
        if (is_string($value) && is_numeric($value)) {
            $value = floatval($value);
        } elseif (!is_numeric($value)) {
            return false;
        }

        // Check minimum value if set
        if ($this->min !== null && $value < $this->min) {
            return false;
        }

        // Check maximum value if set
        if ($this->max !== null && $value > $this->max) {
            return false;
        }

        return apply_filters('wp_settings_number_range_validator_result', true, $value, $this->min, $this->max);
    }

    /**
     * Gets the error message for when validation fails.
     *
     * @return string The error message indicating the range constraint.
     */
    public function getMessage(): string
    {
        if ($this->min !== null && $this->max !== null) {
            return sprintf(
                __('Please enter a value between %s and %s.', 'settings-manager'),
                $this->min,
                $this->max
            );
        } elseif ($this->min !== null) {
            return sprintf(
                __('Please enter a value greater than or equal to %s.', 'settings-manager'),
                $this->min
            );
        } elseif ($this->max !== null) {
            return sprintf(
                __('Please enter a value less than or equal to %s.', 'settings-manager'),
                $this->max
            );
        }

        return __('Please enter a valid number.', 'settings-manager');
    }

    /**
     * Gets the name of this validation rule.
     *
     * @return string The identifier for this validator.
     */
    public function getName(): string
    {
        return 'number_range';
    }

    /**
     * Gets the parameters used by this validator.
     *
     * @return array<string, mixed> An array containing the range parameters.
     */
    public function getParameters(): array
    {
        return [
            'min' => $this->min,
            'max' => $this->max
        ];
    }
}