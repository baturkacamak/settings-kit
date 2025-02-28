<?php

namespace WPSettingsKit\Validation\Rules\Select;

use WPSettingsKit\Attribute\ValidationRule;
use WPSettingsKit\Validation\Base\Interface\IValidationRule;

/**
 * Validates that selections form a valid hierarchical path.
 */
#[ValidationRule(
    type: ['select'],
    method: 'addHierarchicalPathValidation',
    priority: 65
)]
class HierarchicalPathValidationRule implements IValidationRule
{
    /**
     * @var array<string, array<string>> Tree structure representing valid parent-child relationships
     */
    private array $hierarchyTree;

    /**
     * @var string Custom error message
     */
    private readonly string $customMessage;

    /**
     * Constructor for HierarchicalPathValidator.
     *
     * @param array<string, array<string>> $hierarchyTree Tree structure of valid parent-child relationships
     * @param string|null $customMessage Optional custom error message
     */
    public function __construct(array $hierarchyTree, ?string $customMessage = null)
    {
        $this->hierarchyTree = $hierarchyTree;
        $this->customMessage = $customMessage ??
            __('The selected options do not form a valid hierarchical path.', 'wp-settings-kit');
    }

    /**
     * Validates if selections form a valid path in the hierarchy.
     *
     * @param mixed $value The values to validate (expected to be an array)
     * @return bool True if selections form a valid hierarchical path, false otherwise
     */
    public function validate(mixed $value): bool
    {
        if (!is_array($value) || count($value) < 2) {
            return true; // Single selection or non-array is valid
        }

        // Check each parent-child relationship in sequence
        for ($i = 0; $i < count($value) - 1; $i++) {
            $parent = $value[$i];
            $child  = $value[$i + 1];

            // If parent doesn't exist in hierarchy or child is not a valid child of parent
            if (!isset($this->hierarchyTree[$parent]) || !in_array($child, $this->hierarchyTree[$parent])) {
                return false;
            }
        }

        return apply_filters('wp_settings_hierarchical_path_validator_result', true, $value, $this->hierarchyTree);
    }

    /**
     * Gets the error message for when validation fails.
     *
     * @return string The error message indicating invalid hierarchical path
     */
    public function getMessage(): string
    {
        return apply_filters('wp_settings_hierarchical_path_validator_message', $this->customMessage);
    }

    /**
     * Gets the name of this validation rule.
     *
     * @return string The identifier for this validator
     */
    public function getName(): string
    {
        return 'hierarchical_path';
    }

    /**
     * Gets the parameters used by this validator.
     *
     * @return array<string, mixed> An array containing validation parameters
     */
    public function getParameters(): array
    {
        return [
            'hierarchyTree' => $this->hierarchyTree,
            'customMessage' => $this->customMessage,
        ];
    }
}