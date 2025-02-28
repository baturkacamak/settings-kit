<?php

namespace WPSettingsKit\Validation\Rules\Numeric;

use WPSettingsKit\Attribute\ValidationRule;
use WPSettingsKit\Validation\Base\Interface\IValidationRule;

/**
 * Numeric validation rule.
 *
 * Validates that a value is a number and optionally within a specified range.
 */
#[ValidationRule(
    type: ['text', 'number'],
    method: 'addNumericValidation',
    priority: 10
)]
class NumericValidationRule implements IValidationRule
{
    /**
     * @var float|null Minimum allowed value
     */
    private ?float $min;

    /**
     * @var float|null Maximum allowed value
     */
    private ?float $max;

    /**
     * @var bool Whether to allow integers only
     */
    private bool $integerOnly;

    /**
     * @var string Custom error message
     */
    private string $customMessage;

    /**
     * Constructor.
     *
     * @param float|null $min Minimum allowed value (null for no minimum)
     * @param float|null $max Maximum allowed value (null for no maximum)
     * @param bool $integerOnly Whether to allow integers only
     * @param string|null $customMessage Optional custom error message
     */
    public function __construct(
        ?float  $min = null,
        ?float  $max = null,
        bool    $integerOnly = false,
        ?string $customMessage = null
    )
    {
        $this->min           = $min;
        $this->max           = $max;
        $this->integerOnly   = $integerOnly;
        $this->customMessage = $customMessage ?? $this->generateDefaultMessage();
    }

    /**
     * Generates a default error message based on constraints.
     *
     * @return string Default error message
     */
    private function generateDefaultMessage(): string
    {
        $type = $this->integerOnly ? __('integer', 'wp-settings-kit') : __('number', 'wp-settings-kit');

        if ($this->min !== null && $this->max !== null) {
            return sprintf(
                __('Please enter a valid %1$s between %2$s and %3$s.', 'wp-settings-kit'),
                $type,
                $this->min,
                $this->max
            );
        } else if ($this->min !== null) {
            return sprintf(
                __('Please enter a valid %1$s greater than or equal to %2$s.', 'wp-settings-kit'),
                $type,
                $this->min
            );
        } else if ($this->max !== null) {
            return sprintf(
                __('Please enter a valid %1$s less than or equal to %2$s.', 'wp-settings-kit'),
                $type,
                $this->max
            );
        }

        return sprintf(__('Please enter a valid %s.', 'wp-settings-kit'), $type);
    }

    /**
     * {@inheritdoc}
     */
    public function validate(mixed $value): bool
    {
        // Handle empty values
        if ($value === null || $value === '') {
            return false;
        }

        // Convert to string if it's not already
        if (!is_string($value) && !is_numeric($value)) {
            return false;
        }

        $stringValue = (string)$value;

        // Check if it's numeric
        if (!is_numeric($stringValue)) {
            return false;
        }

        // Convert to float for comparison
        $numericValue = (float)$stringValue;

        // Check integer constraint
        if ($this->integerOnly && floor($numericValue) != $numericValue) {
            return false;
        }

        // Check min/max constraints
        if ($this->min !== null && $numericValue < $this->min) {
            return false;
        }

        if ($this->max !== null && $numericValue > $this->max) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage(): string
    {
        return apply_filters('wp_settings_numeric_validator_message', $this->customMessage);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'numeric';
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(): array
    {
        return [
            'min'           => $this->min,
            'max'           => $this->max,
            'integerOnly'   => $this->integerOnly,
            'customMessage' => $this->customMessage,
        ];
    }
}