<?php

namespace WPSettingsKit\WordPress;

use WPSettingsKit\Exception\RepositoryException;
use WPSettingsKit\WordPress\Interface\ISettingsRepository;

/**
 * WordPress options-based settings repository implementation
 */
class WPSettingsRepository implements ISettingsRepository
{
    private string $prefix;
    private bool $autoload;
    private bool $isNetwork;

    /**
     * Constructor
     *
     * @param string $prefix Prefix for all option names
     * @param bool $autoload Whether to autoload the options
     * @param bool $isNetwork Whether to use network options
     */
    public function __construct(
        string $prefix = 'settings_',
        bool   $autoload = true,
        bool   $isNetwork = false
    )
    {
        $this->prefix    = $prefix;
        $this->autoload  = $autoload;
        $this->isNetwork = $isNetwork;
    }

    /**
     * @inheritDoc
     */
    public function get(string $key): mixed
    {
        $optionName = $this->prefix . $key;
        return $this->isNetwork ? get_site_option($optionName, null) : get_option($optionName, null);
    }

    /**
     * @inheritDoc
     * @throws RepositoryException
     */
    public function set(string $key, mixed $value): bool
    {
        $optionName = $this->prefix . $key;
        $result     = $this->isNetwork
            ? update_site_option($optionName, $value)
            : update_option($optionName, $value, $this->autoload);
        if (!$result) {
            throw new RepositoryException("Failed to save setting for key: {$key}");
        }
        return true;
    }

    /**
     * @inheritDoc
     * @throws RepositoryException
     */
    public function delete(string $key): bool
    {
        $optionName = $this->prefix . $key;
        $result     = $this->isNetwork ? delete_site_option($optionName) : delete_option($optionName);
        if (!$result) {
            throw new RepositoryException("Failed to delete setting for key: {$key}");
        }
        return true;
    }

    /**
     * Delete all settings with the current prefix
     *
     * @return bool
     */
    public function deleteAll(): bool
    {
        global $wpdb;

        $table     = $this->isNetwork ? $wpdb->sitemeta : $wpdb->options;
        $keyColumn = $this->isNetwork ? 'meta_key' : 'option_name';

        $sql = $wpdb->prepare(
            "DELETE FROM {$table} WHERE {$keyColumn} LIKE %s",
            $wpdb->esc_like($this->prefix) . '%'
        );

        $result = $wpdb->query($sql);

        if ($result !== false) {
            do_action('settings_manager_after_delete_all', $this);
            return true;
        }

        return false;
    }

    /**
     * Get all settings with the current prefix
     *
     * @return array
     */
    public function getAll(): array
    {
        global $wpdb;

        $table       = $this->isNetwork ? $wpdb->sitemeta : $wpdb->options;
        $keyColumn   = $this->isNetwork ? 'meta_key' : 'option_name';
        $valueColumn = $this->isNetwork ? 'meta_value' : 'option_value';

        $sql = $wpdb->prepare(
            "SELECT {$keyColumn}, {$valueColumn} FROM {$table} WHERE {$keyColumn} LIKE %s",
            $wpdb->esc_like($this->prefix) . '%'
        );

        $results  = $wpdb->get_results($sql);
        $settings = [];

        foreach ($results as $row) {
            $key            = substr($row->$keyColumn, strlen($this->prefix));
            $settings[$key] = maybe_unserialize($row->$valueColumn);
        }

        return apply_filters('settings_manager_get_all', $settings, $this);
    }

    /**
     * Check if a setting exists
     *
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool
    {
        $optionName = $this->prefix . $key;

        if ($this->isNetwork) {
            return get_site_option($optionName, null) !== null;
        }

        return get_option($optionName, null) !== null;
    }

    /**
     * Get the current prefix
     *
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * Check if using network options
     *
     * @return bool
     */
    public function isNetwork(): bool
    {
        return $this->isNetwork;
    }

    /**
     * Check if options are autoloaded
     *
     * @return bool
     */
    public function isAutoload(): bool
    {
        return $this->autoload;
    }
}