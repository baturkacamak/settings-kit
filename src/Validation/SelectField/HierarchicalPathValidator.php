<?php

namespace WPSettingsKit\Validation\SelectField;

use WPSettingsKit\Validation\Interface\IValidationRule;

/**
 * Validates that selections form a valid hierarchical path.
 */
class HierarchicalPathValidator implements IValidationRule
{
    /**
     * @var array<string, array<string>> Tree structure representing valid parent-child relationships
     */
    private array $hierarchyTree;

    /**
     * Constructor for HierarchicalPathValidator.
     *
     * @param array<string, array<string>> $hierarchyTree Tree structure of valid parent-child relationships
     */
    public function __construct(array $hierarchyTree)
    {
        $this->hierarchyTree = $hierarchyTree;
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
            $child = $value[$i + 1];

            // If parent doesn't exist in hierarchy or child is not a valid child of parent
            if (!isset($this->hierarchyTree[$parent]) || !in_array($child, $this->hierarchyTree[$parent])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Gets the error message for when validation fails.
     *
     * @return string The error message indicating invalid hierarchical path
     */
    public function getMessage(): string
    {
        return __('The selected options do not form a valid hierarchical path.', 'settings-manager');
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
        return ['hierarchyTree' => $this->hierarchyTree];
    }
}