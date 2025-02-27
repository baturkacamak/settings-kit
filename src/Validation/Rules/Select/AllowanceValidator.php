<?php

namespace WPSettingsKit\Validation\Rules\SelectField;

use WPSettingsKit\Validation\Base\Interface\IValidationRule;

/**
 * Validates that the combined selections meet a budget/allowance constraint.
 */
class AllowanceValidator implements IValidationRule
{
    /**
     * @var array<string, int> Values representing the "cost" of each option
     */
    private array $optionCosts;

    /**
     * @var int Maximum total allowance/budget permitted
     */
    private int $maxAllowance;

    /**
     * Constructor for AllowanceValidator.
     *
     * @param array<string, int> $optionCosts The "cost" values for each option
     * @param int $maxAllowance The maximum total allowance/budget permitted
     */
    public function __construct(array $optionCosts, int $maxAllowance)
    {
        $this->optionCosts = $optionCosts;
        $this->maxAllowance = $maxAllowance;
    }

    /**
     * Validates if the total "cost" of selections is within allowance.
     *
     * @param mixed $value The value to validate (string or array)
     * @return bool True if total is within allowance, false otherwise
     */
    public function validate(mixed $value): bool
    {
        $total = 0;

        if (is_array($value)) {
            foreach ($value as $option) {
                $total += $this->optionCosts[$option] ?? 0;
            }
        } else {
            $total = $this->optionCosts[$value] ?? 0;
        }

        return $total <= $this->maxAllowance;
    }

    /**
     * Gets the error message for when validation fails.
     *
     * @return string The error message indicating the allowance is exceeded
     */
    public function getMessage(): string
    {
        return sprintf(
            __('Your selections exceed the maximum allowance of %d.', 'settings-manager'),
            $this->maxAllowance
        );
    }

    /**
     * Gets the name of this validation rule.
     *
     * @return string The identifier for this validator
     */
    public function getName(): string
    {
        return 'allowance';
    }

    /**
     * Gets the parameters used by this validator.
     *
     * @return array<string, mixed> An array containing validation parameters
     */
    public function getParameters(): array
    {
        return [
            'optionCosts' => $this->optionCosts,
            'maxAllowance' => $this->maxAllowance
        ];
    }
}