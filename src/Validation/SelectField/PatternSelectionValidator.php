<?php

namespace WPSettingsKit\Validation\SelectField;

use WPSettingsKit\Validation\Interface\IValidationRule;

/**
 * Validates that a selection matches a specific pattern or format.
 */
class PatternSelectionValidator implements IValidationRule
{
    /**
     * @var string The regex pattern to match against
     */
    private string $pattern;

    /**
     * @var string Description of the pattern for error messages
     */
    private string $patternDescription;

    /**
     * Constructor for PatternSelectionValidator.
     *
     * @param string $pattern The regex pattern for validation
     * @param string $patternDescription Human-readable description of the pattern
     */
    public function __construct(string $pattern, string $patternDescription = '')
    {
        $this->pattern = $pattern;
        $this->patternDescription = $patternDescription;
    }

    /**
     * Validates if the selection matches the required pattern.
     *
     * @param mixed $value The value to validate
     * @return bool True if selection matches pattern, false otherwise
     */
    public function validate(mixed $value): bool
    {
        if (is_array($value)) {
            // For multi-select, all items must match the pattern
            foreach ($value as $item) {
                if (!is_string($item) || !preg_match($this->pattern, $item)) {
                    return false;
                }
            }
            return true;
        }

        // For single select
        if (!is_string($value)) {
            return false;
        }

        return preg_match($this->pattern, $value) === 1;
    }

    /**
     * Gets the error message for when validation fails.
     *
     * @return string The error message indicating the selection format is invalid
     */
    public function getMessage(): string
    {
        if ($this->patternDescription) {
            return sprintf(
                __('The selected option must match the format: %s', 'settings-manager'),
                $this->patternDescription
            );
        }

        return __('The selected option is in an invalid format.', 'settings-manager');
    }

    /**
     * Gets the name of this validation rule.
     *
     * @return string The identifier for this validator
     */
    public function getName(): string
    {
        return 'pattern_selection';
    }

    /**
     * Gets the parameters used by this validator.
     *
     * @return array<string, mixed> An array containing validation parameters
     */
    public function getParameters(): array
    {
        return [
            'pattern' => $this->pattern,
            'patternDescription' => $this->patternDescription
        ];
    }
}