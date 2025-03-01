<?php

namespace  WPSettingsKit\Infrastructure\Platform\WordPress\Interface;


/**
 * Interface for settings storage
 */
interface ISettingsRepository {
    /**
     * Get a setting value
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed;

    /**
     * Set a setting value
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function set(string $key, mixed $value): bool;

    /**
     * Delete a setting
     *
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool;
}