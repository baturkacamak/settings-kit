<?php

namespace WPSettingsKit\Validation\Rules\SelectField;

use WPSettingsKit\Attribute\ValidationRule;
use WPSettingsKit\Validation\Base\Interface\IValidationRule;

/**
 * Validates that at least one selection from each required category is made.
 */
#[ValidationRule(
    type: ['select', 'checkbox'],
    method: 'addRequiredCategoriesValidation',
    priority: 55
)]
class RequiredCategoriesValidationRule implements IValidationRule
{
    /**
     * @var array<string, string> Map of option values to their category
     */
    private array $categoryMap;

    /**
     * @var array<string> List of categories that must have at least one selection
     */
    private array $requiredCategories;

    /**
     * @var string Custom error message
     */
    private readonly string $customMessage;

    /**
     * Constructor for RequiredCategoriesValidator.
     *
     * @param array<string, string> $categoryMap Map of option values to their category
     * @param array<string> $requiredCategories List of categories that must have selections
     * @param string|null $customMessage Optional custom error message
     */
    public function __construct(
        array   $categoryMap,
        array   $requiredCategories,
        ?string $customMessage = null
    )
    {
        $this->categoryMap        = $categoryMap;
        $this->requiredCategories = $requiredCategories;

        $this->customMessage = $customMessage ?? sprintf(
            __('You must select at least one option from each of these categories: %s', 'wp-settings-kit'),
            implode(', ', $requiredCategories)
        );
    }

    /**
     * Validates if at least one selection from each required category is made.
     *
     * @param mixed $value The value to validate (expected to be an array for multi-select)
     * @return bool True if all required categories have selections, false otherwise
     */
    public function validate(mixed $value): bool
    {
        if (!is_array($value)) {
            // For single selection, check if it belongs to any required category
            if (empty($value)) {
                return empty($this->requiredCategories);
            }

            $category = $this->categoryMap[$value] ?? null;
            return $category && count($this->requiredCategories) === 1 &&
                in_array($category, $this->requiredCategories);
        }

        // For multi-select
        $selectedCategories = [];

        foreach ($value as $option) {
            if (isset($this->categoryMap[$option])) {
                $selectedCategories[] = $this->categoryMap[$option];
            }
        }

        // Check if all required categories are present
        foreach ($this->requiredCategories as $required) {
            if (!in_array($required, $selectedCategories)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Gets the error message for when validation fails.
     *
     * @return string The error message
     */
    public function getMessage(): string
    {
        return apply_filters('wp_settings_required_categories_validator_message', $this->customMessage);
    }

    /**
     * Gets the name of this validation rule.
     *
     * @return string The identifier for this validator
     */
    public function getName(): string
    {
        return 'required_categories';
    }

    /**
     * Gets the parameters used by this validator.
     *
     * @return array<string, mixed> An array containing validation parameters
     */
    public function getParameters(): array
    {
        return [
            'categoryMap'        => $this->categoryMap,
            'requiredCategories' => $this->requiredCategories,
            'customMessage'      => $this->customMessage,
        ];
    }
}