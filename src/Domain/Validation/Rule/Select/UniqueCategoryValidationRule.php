<?php

namespace WPSettingsKit\Domain\Validation\Rules\Select;

use WPSettingsKit\Domain\Field\Enhancer\Attribute\ValidationRule;
use WPSettingsKit\Domain\Validation\Base\Interface\IValidationRule;

/**
 * Validates that the selected options represent unique categories.
 */
#[ValidationRule(
    type: ['select', 'checkbox'],
    method: 'addUniqueCategoryValidation',
    priority: 60
)]
class UniqueCategoryValidationRule implements IValidationRule
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
     * @var string Custom error message
     */
    private readonly string $customMessage;

    /**
     * Constructor for UniqueCategoryValidator.
     *
     * @param array<string, string> $categoryMap Map of option values to their category
     * @param int $maxPerCategory Maximum selections allowed per category
     * @param string|null $customMessage Optional custom error message
     */
    public function __construct(
        array   $categoryMap,
        int     $maxPerCategory = 1,
        ?string $customMessage = null
    )
    {
        $this->categoryMap    = $categoryMap;
        $this->maxPerCategory = $maxPerCategory;

        $this->customMessage = $customMessage ?? ($maxPerCategory === 1
            ? __('You can only select one option from each category.', 'wp-settings-kit')
            : sprintf(
                __('You can only select up to %d options from each category.', 'wp-settings-kit'),
                $maxPerCategory
            )
        );
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

        $result = true;
        return apply_filters('wp_settings_unique_category_validator_result', $result, $value, $this->categoryMap, $this->maxPerCategory);
    }

    /**
     * Gets the error message for when validation fails.
     *
     * @return string The error message
     */
    public function getMessage(): string
    {
        return apply_filters('wp_settings_unique_category_validator_message', $this->customMessage);
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
            'categoryMap'    => $this->categoryMap,
            'maxPerCategory' => $this->maxPerCategory,
            'customMessage'  => $this->customMessage,
        ];
    }
}