<?php

namespace WPSettingsKit\Validation\Rules\SelectField;

use WPSettingsKit\Validation\Base\Interface\IValidationRule;

/**
 * Validates that a selected option exists in the available options array.
 */
class OptionExistsValidator implements IValidationRule
{
    /**
     * @var array<string, string> The available options for validation
     */
    private array $availableOptions;

    /**
     * Constructor for OptionExistsValidator.
     *
     * @param array<string, string> $availableOptions The available options to validate against
     */
    public function __construct(array $availableOptions)
    {
        $this->availableOptions = $availableOptions;
    }

    /**
     * Validates if the selected value exists in available options.
     *
     * @param mixed $value The value to validate
     * @return bool True if value exists in available options, false otherwise
     */
    public function validate(mixed $value): bool
    {
        if (is_array($value)) {
            // For multi-select fields, check if all selected values exist
            foreach ($value as $singleValue) {
                if (!array_key_exists($singleValue, $this->availableOptions)) {
                    return false;
                }
            }
            return true;
        }

        // Single select validation
        $result = array_key_exists($value, $this->availableOptions);
        return apply_filters('wp_settings_option_exists_validator_result', $result, $value, $this->availableOptions);
    }

    /**
     * Gets the error message for when validation fails.
     *
     * @return string The error message indicating the selected option is invalid
     */
    public function getMessage(): string
    {
        $message = __('The selected option is invalid.', 'settings-manager');
        return apply_filters('wp_settings_option_exists_validator_message', $message);
    }

    /**
     * Gets the name of this validation rule.
     *
     * @return string The identifier for this validator
     */
    public function getName(): string
    {
        return 'option_exists';
    }

    /**
     * Gets the parameters used by this validator.
     *
     * @return array<string, mixed> An array containing validation parameters
     */
    public function getParameters(): array
    {
        return ['availableOptions' => $this->availableOptions];
    }
}