<?php

namespace WPSettingsKit\Validation\SelectField;

use WPSettingsKit\Validation\Interface\IValidationRule;

/**
 * Validates that selected option(s) from one field are compatible with another field's selection.
 */
class DependentSelectionValidator implements IValidationRule
{
    /**
     * @var string The key of the field this validation depends on
     */
    private string $dependentFieldKey;

    /**
     * @var array<string, array<string>> Map of allowed values based on dependent field values
     */
    private array $allowedValueMap;

    /**
     * @var mixed|null The current value of the dependent field
     */
    private mixed $dependentFieldValue = null;

    /**
     * Constructor for DependentSelectionValidator.
     *
     * @param string $dependentFieldKey The key of the field this validation depends on
     * @param array<string, array<string>> $allowedValueMap Map of allowed values based on dependent field values
     */
    public function __construct(string $dependentFieldKey, array $allowedValueMap)
    {
        $this->dependentFieldKey = $dependentFieldKey;
        $this->allowedValueMap   = $allowedValueMap;
    }

    /**
     * Sets the current value of the dependent field for validation.
     *
     * @param mixed $value The current value of the dependent field
     * @return void
     */
    public function setDependentFieldValue(mixed $value): void
    {
        $this->dependentFieldValue = $value;
    }

    /**
     * Validates if the selection is compatible with the dependent field's value.
     *
     * @param mixed $value The value to validate
     * @return bool True if selection is compatible, false otherwise
     */
    public function validate(mixed $value): bool
    {
        if ($this->dependentFieldValue === null) {
            // If dependent value not set, we can't validate
            return true;
        }

        $dependentKey = (string)$this->dependentFieldValue;

        // If no mapping exists for the dependent value, consider it valid
        if (!isset($this->allowedValueMap[$dependentKey])) {
            return true;
        }

        $allowedValues = $this->allowedValueMap[$dependentKey];

        if (is_array($value)) {
            // For multi-select, all selected values must be allowed
            foreach ($value as $singleValue) {
                if (!in_array($singleValue, $allowedValues)) {
                    return false;
                }
            }
            return true;
        }

        // Single select validation
        $result = in_array($value, $allowedValues);
        return apply_filters('wp_settings_dependent_selection_validator_result', $result, $value, $this->dependentFieldValue, $this->allowedValueMap);
    }

    /**
     * Gets the error message for when validation fails.
     *
     * @return string The error message indicating the selection is incompatible
     */
    public function getMessage(): string
    {
        $message = __('The selected option is not compatible with related field values.', 'settings-manager');
        return apply_filters('wp_settings_dependent_selection_validator_message', $message, $this->dependentFieldKey);
    }

    /**
     * Gets the name of this validation rule.
     *
     * @return string The identifier for this validator
     */
    public function getName(): string
    {
        return 'dependent_selection';
    }

    /**
     * Gets the parameters used by this validator.
     *
     * @return array<string, mixed> An array containing validation parameters
     */
    public function getParameters(): array
    {
        return [
            'dependentFieldKey'   => $this->dependentFieldKey,
            'allowedValueMap'     => $this->allowedValueMap,
            'dependentFieldValue' => $this->dependentFieldValue,
        ];
    }
}