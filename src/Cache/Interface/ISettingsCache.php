<?php

namespace WPSettingsKit\Cache\Interface;

use WPSettingsKit\Exception\CacheException;

/**
 * Interface for settings cache implementations
 *
 * Defines the contract for caching settings in the WPSettingsKit framework.
 * Implementations must provide methods for storing, retrieving, and managing cached values.
 */
interface ISettingsCache
{
    /**
     * Retrieves a value from the cache
     *
     * @param string $key The unique key identifying the cached value
     * @return mixed The cached value, or null if not found or caching is disabled
     */
    public function get(string $key): mixed;

    /**
     * Stores a value in the cache
     *
     * @param string $key The unique key identifying the value
     * @param mixed $value The value to cache
     * @param int|null $ttl Time to live in seconds, or null to use the default TTL
     * @throws CacheException If the cache operation fails
     */
    public function set(string $key, mixed $value, ?int $ttl = null): void;

    /**
     * Deletes a specific value from the cache
     *
     * @param string $key The unique key identifying the value to delete
     */
    public function delete(string $key): void;

    /**
     * Clears all cached values
     *
     * Removes all cache entries associated with the implementation's prefix.
     *
     * @throws CacheException If the flush operation fails
     */
    public function flush(): void;

    /**
     * Deletes cache entries matching a specific pattern
     *
     * @param string $pattern The pattern to match cache keys (e.g., 'user_*')
     * @throws CacheException If the invalidation operation fails
     */
    public function invalidatePattern(string $pattern): void;

    /**
     * Stores multiple key-value pairs in the cache
     *
     * @param array<string, mixed> $items Associative array of key-value pairs to cache
     * @param int|null $ttl Time to live in seconds, or null to use the default TTL
     * @throws CacheException If any cache operation fails
     */
    public function setMultiple(array $items, ?int $ttl = null): void;
}