<?php

namespace  WPSettingsKit\Domain\Field\Base\Interface;

/**
 * Interface for field implementations
 */
interface IField {
    /**
     * Render the field
     */
    public function render(): string;

    /**
     * Validate the field value
     *
     * @return bool
     */
    public function validate(): bool;

    /**
     * Sanitize the field value
     *
     * @return mixed
     */
    public function sanitize(): mixed;

    /**
     * Get the field value
     *
     * @return mixed
     */
    public function getValue(): mixed;

    /**
     * Set the field value
     *
     * @param mixed $value
     */
    public function setValue(mixed $value): void;

    /**
     * Get the field key
     *
     * @return string
     */
    public function getKey(): string;

    /**
     * Get the field label
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Check if the field is required
     *
     * @return bool
     */
    public function isRequired(): bool;
}
