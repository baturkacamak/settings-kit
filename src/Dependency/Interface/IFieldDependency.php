<?php

namespace  WPSettingsKit\Dependency\Interface;

use WPSettingsKit\Field\Base\Interface\IField;

interface IFieldDependency {
    /**
     * Evaluate the dependency condition
     *
     * @param IField $field Field to evaluate against
     * @return bool True if dependency is satisfied, false otherwise
     */
    public function evaluate(IField $field): bool;

    /**
     * Get the target field key
     *
     * @return string Target field key
     */
    public function getTargetField(): string;

    /**
     * Get the condition operator
     *
     * @return string Condition operator
     */
    public function getCondition(): string;

    /**
     * Get the comparison value
     *
     * @return mixed Value to compare against
     */
    public function getValue(): mixed;
}
