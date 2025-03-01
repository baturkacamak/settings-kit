<?php

namespace  WPSettingsKit\Application\Service\Interface;

/**
 * Core interface for managing settings
 */
interface ISettingsManager {
    /**
     * Register settings with the system
     */
    public
    function register(): void;

    /**
     * Initialize fields with their default values
     */
    public function initializeFields(): void;

    /**
     * Save all settings
     */
    public function save(): bool;

    /**
     * Get a setting value by key
     *
     * @param string $key The setting key
     * @return mixed The setting value
     */
    public function getValue(string $key): mixed;

    /**
     * Set a setting value
     *
     * @param string $key The setting key
     * @param mixed $value The setting value
     */
    public function setValue(string $key, mixed $value): void;
}
