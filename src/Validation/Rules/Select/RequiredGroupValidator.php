<?php

namespace WPSettingsKit\Validation\Rules\SelectField;

use WPSettingsKit\Validation\Base\Interface\IValidationRule;

/**
 * Validates that an option from a specific option group is selected.
 */
class RequiredGroupValidator implements IValidationRule
{
    /**
     * @var string The option group that must have a selection
     */
    private string $requiredGroup;

    /**
     * @var array<string, string> Map of option values to their group names
     */
    private array $optionGroupMap;

    /**
     * Constructor for RequiredGroupValidator.
     *
     * @param string $requiredGroup The name of the option group that must have a selection
     * @param array<string, string> $optionGroupMap Map of option values to their group names
     */
    public function __construct(string $requiredGroup, array $optionGroupMap)
    {
        $this->requiredGroup = $requiredGroup;
        $this->optionGroupMap = $optionGroupMap;
    }

    /**
     * Validates if an option from the required group is selected.
     *
     * @param mixed $value The value to validate (string or array of selected options)
     * @return bool True if an option from the required group is selected, false otherwise
     */
    public function validate(mixed $value): bool
    {
        if (empty($value)) {
            return false;
        }

        if (is_array($value)) {
            // For multi-select, check if any selected value belongs to the required group
            foreach ($value as $singleValue) {
                if (isset($this->optionGroupMap[$singleValue]) &&
                    $this->optionGroupMap[$singleValue] === $this->requiredGroup) {
                    return true;
                }
            }
            return false;
        }

        // Single select validation
        $result = isset($this->optionGroupMap[$value]) &&
            $this->optionGroupMap[$value] === $this->requiredGroup;

        return apply_filters('wp_settings_required_group_validator_result', $result, $value, $this->requiredGroup, $this->optionGroupMap);
    }

    /**
     * Gets the error message for when validation fails.
     *
     * @return string The error message indicating a selection from the required group is needed
     */
    public function getMessage(): string
    {
        $message = sprintf(
            __('Please select an option from the "%s" group.', 'settings-manager'),
            $this->requiredGroup
        );
        return apply_filters('wp_settings_required_group_validator_message', $message, $this->requiredGroup);
    }

    /**
     * Gets the name of this validation rule.
     *
     * @return string The identifier for this validator
     */
    public function getName(): string
    {
        return 'required_group';
    }

    /**
     * Gets the parameters used by this validator.
     *
     * @return array<string, mixed> An array containing validation parameters
     */
    public function getParameters(): array
    {
        return [
            'requiredGroup' => $this->requiredGroup,
            'optionGroupMap' => $this->optionGroupMap,
        ];
    }
}