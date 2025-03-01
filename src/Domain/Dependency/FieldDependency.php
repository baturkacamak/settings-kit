<?php

namespace WPSettingsKit\Domain\Dependency;

use WPSettingsKit\Domain\Dependency\Interface\IFieldDependency;
use WPSettingsKit\Domain\Field\Base\Interface\IField;

/**
 * Defines a dependency between fields, controlling visibility or behavior based on another field's value.
 */
class FieldDependency implements IFieldDependency
{
    /**
     * @var string The key of the target field this dependency relies on.
     */
    private string $targetField;

    /**
     * @var string The condition to evaluate (e.g., '=', '!=', 'contains').
     */
    private string $condition;

    /**
     * @var mixed The value to compare against.
     */
    private mixed $value;

    /**
     * Constructor for FieldDependency.
     *
     * @param string $targetField The key of the field this dependency targets.
     * @param string $condition The condition to evaluate (e.g., '=', '!=').
     * @param mixed $value The value to compare the target field's value against.
     */
    public function __construct(string $targetField, string $condition, mixed $value)
    {
        $this->targetField = $targetField;
        $this->condition   = $condition;
        $this->value       = $value;
    }

    /**
     * Evaluates the dependency condition against the target field's value.
     *
     * @param IField $field The field whose value is used for evaluation.
     * @return bool True if the dependency condition is met, false otherwise.
     */
    public function evaluate(IField $field): bool
    {
        $targetValue = $field->getValue();
        $result      = match ($this->condition) {
            '=' => $targetValue == $this->value,
            '!=' => $targetValue != $this->value,
            '>' => $targetValue > $this->value,
            '<' => $targetValue < $this->value,
            '>=' => $targetValue >= $this->value,
            '<=' => $targetValue <= $this->value,
            'contains' => is_string($targetValue) && str_contains($targetValue, $this->value),
            'in' => in_array($targetValue, (array)$this->value),
            'not_in' => !in_array($targetValue, (array)$this->value),
            'empty' => empty($targetValue),
            'not_empty' => !empty($targetValue),
            default => false,
        };
        return apply_filters('wp_settings_field_dependency_result', $result, $field, $this);
    }

    /**
     * Gets the value to compare against.
     *
     * @return mixed The comparison value.
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    // Mevcut getter metodları için PHPDoc...

    /**
     * Gets the HTML data attributes for frontend dependency handling.
     *
     * @return string A string of HTML data attributes for JavaScript integration.
     */
    public function getDataAttributes(): string
    {
        return sprintf(
            'data-dependency-target="%s" data-dependency-condition="%s" data-dependency-value="%s"',
            esc_attr($this->targetField),
            esc_attr($this->condition),
            esc_attr(json_encode($this->value))
        );
    }

    /**
     * Gets the target field key.
     *
     * @return string The key of the target field.
     */
    public function getTargetField(): string
    {
        return $this->targetField;
    }

    /**
     * Gets the condition of the dependency.
     *
     * @return string The condition to evaluate.
     */
    public function getCondition(): string
    {
        return $this->condition;
    }
}