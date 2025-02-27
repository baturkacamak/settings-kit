<?php

namespace WPSettingsKit\Validation\SelectField;

use WPSettingsKit\Validation\Interface\IValidationRule;

/**
 * Validates that the selected options represent unique categories.
 */
class UniqueCategoryValidator implements IValidationRule
{
    /**
     * @var array<string, string> Map of option values to their category
     */
    private array $categoryMap;

    /**
     * @var int Maximum number of options allowed per category
     */
    private int $maxPerCategory;

    /**
     * Constructor for UniqueCategoryValidator.
     *
     * @param array<string, string> $categoryMap Map of option values to their category
     * @param int $maxPerCategory Maximum selections allowed per category
     */
    public function __construct(array $categoryMap, int $maxPerCategory = 1)
    {
        $this->categoryMap = $categoryMap;
        $this->maxPerCategory = $maxPerCategory;
    }

    /**
     * Validates if the selections maintain category uniqueness rules.
     *
     * @param mixed $value The value to validate (expected to be an array for multi-select)
     * @return bool True if category uniqueness is maintained, false otherwise
     */
    public function validate(mixed $value): bool
    {
        if (!is_array($value)) {
            return true; // Single selection is always valid
        }

        $categoryCounts = [];

        foreach ($value as $selectedOption) {
            if (!isset($this->categoryMap[$selectedOption])) {
                continue; // Skip options not in our category map
            }

            $category = $this->categoryMap[$selectedOption];

            if (!isset($categoryCounts[$category])) {
                $categoryCounts[$category] = 0;
            }

            $categoryCounts[$category]++;

            if ($categoryCounts[$category] > $this->maxPerCategory) {
                return false;
            }
        }

        return true;
    }

    /**
     * Gets the error message for when validation fails.
     *
     * @return string The error message indicating category limit is exceeded
     */
    public function getMessage(): string
    {
        if ($this->maxPerCategory === 1) {
            return __('You can only select one option from each category.', 'settings-manager');
        }

        return sprintf(
            __('You can only select up to %d options from each category.', 'settings-manager'),
            $this->maxPerCategory
        );
    }

    /**
     * Gets the name of this validation rule.
     *
     * @return string The identifier for this validator
     */
    public function getName(): string
    {
        return 'unique_category';
    }

    /**
     * Gets the parameters used by this validator.
     *
     * @return array<string, mixed> An array containing validation parameters
     */
    public function getParameters(): array
    {
        return [
            'categoryMap' => $this->categoryMap,
            'maxPerCategory' => $this->maxPerCategory
        ];
    }
}