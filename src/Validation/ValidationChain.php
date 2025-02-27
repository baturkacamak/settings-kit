<?php

namespace WPSettingsKit\Validation;

use WPSettingsKit\Validation\Base\Interface\IValidationRule;

/**
 * Chains multiple validation rules together and applies them to a value.
 */
class ValidationChain
{
    /**
     * @var array<IValidationRule> The list of validation rules in the chain.
     */
    private array $validators = [];

    /**
     * Adds a validator to the chain.
     *
     * @param IValidationRule $validator The validation rule to add.
     * @return void
     */
    public function addValidator(IValidationRule $validator): void
    {
        $this->validators[] = $validator;
        do_action('wp_settings_validation_chain_validator_added', $validator, $this);
    }

    /**
     * Gets all validation error messages for a given value.
     *
     * @param mixed $value The value to check against all validators.
     * @return array<string> An array of error messages from failed validators.
     */
    public function getErrors(mixed $value): array
    {
        $errors = [];
        foreach ($this->validators as $validator) {
            if (!$validator->validate($value)) {
                $errors[] = $validator->getMessage();
            }
        }
        return apply_filters('wp_settings_validation_chain_errors', $errors, $value, $this);
    }

    /**
     * Validates a value against all validators in the chain.
     *
     * @param mixed $value The value to validate.
     * @return bool True if all validators pass, false if any fail.
     */
    public function validate(mixed $value): bool
    {
        foreach ($this->validators as $validator) {
            if (!$validator->validate($value)) {
                do_action('wp_settings_validation_chain_failed', $validator, $value, $this);
                return false;
            }
        }
        do_action('wp_settings_validation_chain_passed', $value, $this);
        return true;
    }
}