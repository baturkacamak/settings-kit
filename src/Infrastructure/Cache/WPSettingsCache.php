<?php

namespace WPSettingsKit\Infrastructure\Cache;

use WPSettingsKit\Domain\Exception\CacheException;
use WPSettingsKit\Infrastructure\Cache\Interface\ISettingsCache;

class WPSettingsCache implements ISettingsCache
{
    private string $prefix;
    private bool $enabled;

    /**
     * Constructor
     *
     * @param string $prefix Prefix for cache keys
     * @param bool $enabled Determines whether caching is enabled
     */
    public function __construct(string $prefix = 'settings_', bool $enabled = true)
    {
        $this->prefix  = $prefix;
        $this->enabled = apply_filters('wp_settings_cache_enabled', $enabled);
    }

    /**
     * Retrieves a value from the cache
     *
     * @param string $key The cache key
     * @return mixed The cached value or null if not found or caching is disabled
     */
    public function get(string $key): mixed
    {
        if (!$this->enabled) {
            return null;
        }
        $value = get_transient($this->prefix . $key);
        return apply_filters('wp_settings_cache_get', $value, $key, $this->prefix);
    }

    /**
     * Stores a value in the cache
     *
     * @param string $key The cache key
     * @param mixed $value The value to store
     * @param ?int $ttl Time to live in seconds, defaults to null (uses filter or DAY_IN_SECONDS)
     * @throws CacheException If caching fails
     */
    public function set(string $key, mixed $value, ?int $ttl = null): void
    {
        if (!$this->enabled) {
            return;
        }

        // Customize TTL via filter
        $ttl = $ttl ?? apply_filters('wp_settings_cache_ttl', DAY_IN_SECONDS, $key, $this->prefix);

        // Allow value customization via filter
        $value = apply_filters('wp_settings_cache_pre_set', $value, $key, $ttl, $this->prefix);

        // Store the transient and throw an exception on failure
        if (!set_transient($this->prefix . $key, $value, $ttl)) {
            throw new CacheException("Failed to set cache for key: {$key}");
        }

        // Trigger a hook after setting the cache
        do_action('wp_settings_cache_after_set', $key, $value, $ttl, $this->prefix);
    }

    /**
     * Deletes a specific key from the cache
     *
     * @param string $key The cache key to delete
     */
    public function delete(string $key): void
    {
        if ($this->enabled) {
            delete_transient($this->prefix . $key);
            do_action('wp_settings_cache_after_delete', $key, $this->prefix);
        }
    }

    /**
     * Clears all cache entries with the current prefix
     *
     * @throws CacheException If the flush operation fails
     */
    public function flush(): void
    {
        if (!$this->enabled) {
            return;
        }

        global $wpdb;

        $sql = $wpdb->prepare(
            "DELETE FROM $wpdb->options WHERE option_name LIKE %s",
            $wpdb->esc_like('_transient_' . $this->prefix) . '%'
        );

        $result = $wpdb->query($sql);
        if ($result === false) {
            throw new CacheException("Failed to flush cache with prefix: {$this->prefix}");
        }

        do_action('wp_settings_cache_after_flush', $this->prefix);
    }

    /**
     * Deletes cache keys matching a specific pattern
     *
     * @param string $pattern Pattern for keys to delete (e.g., 'user_*')
     * @throws CacheException If the invalidation operation fails
     */
    public function invalidatePattern(string $pattern): void
    {
        if (!$this->enabled) {
            return;
        }

        global $wpdb;

        $sql = $wpdb->prepare(
            "DELETE FROM $wpdb->options WHERE option_name LIKE %s",
            $wpdb->esc_like('_transient_' . $this->prefix . $pattern) . '%'
        );

        $result = $wpdb->query($sql);
        if ($result === false) {
            throw new CacheException("Failed to invalidate cache pattern: {$pattern}");
        }

        do_action('wp_settings_cache_after_invalidate', $pattern, $this->prefix);
    }

    /**
     * Stores multiple key-value pairs in the cache in a single operation
     *
     * @param array<string, mixed> $items Array of key-value pairs to store
     * @param ?int $ttl Time to live in seconds, defaults to null (uses filter or DAY_IN_SECONDS)
     * @throws CacheException If any cache set operation fails
     */
    public function setMultiple(array $items, ?int $ttl = null): void
    {
        if (!$this->enabled) {
            return;
        }

        $ttl = $ttl ?? apply_filters('wp_settings_cache_ttl', DAY_IN_SECONDS, 'multiple', $this->prefix);

        foreach ($items as $key => $value) {
            if (!set_transient($this->prefix . $key, $value, $ttl)) {
                throw new CacheException("Failed to set cache for key: {$key} in bulk operation");
            }
        }

        do_action('wp_settings_cache_after_set_multiple', array_keys($items), $ttl, $this->prefix);
    }

    /**
     * Checks if caching is enabled
     *
     * @return bool True if caching is enabled, false otherwise
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Returns the current cache prefix
     *
     * @return string The cache prefix
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }
}