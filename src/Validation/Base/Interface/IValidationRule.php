<?php

namespace  WPSettingsKit\Validation\Base\Interface;

interface IValidationRule
{
    /**
     * Validate a value
     *
     * @param mixed $value Value to validate
     * @return bool True if valid, false otherwise
     */
    public function validate(mixed $value): bool;

    /**
     * Get the validation error message
     *
     * @return string Error message
     */
    public function getMessage(): string;

    /**
     * Get validation rule name
     *
     * @return string Rule name
     */
    public function getName(): string;

    /**
     * Get validation parameters
     *
     * @return array Parameters used in validation
     */
    public function getParameters(): array;
}