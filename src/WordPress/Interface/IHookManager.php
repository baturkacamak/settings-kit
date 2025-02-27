<?php

namespace WPSettingsKit\WordPress\Interface;

interface IHookManager
{
    /**
     * Add an action hook
     *
     * @param string $hookName
     * @param callable $callback
     * @param int $priority
     * @param int $acceptedArgs
     * @return bool
     */
    public function addAction(string $hookName, callable $callback, int $priority = 10, int $acceptedArgs = 1): bool;

    /**
     * Add a filter hook
     *
     * @param string $hookName
     * @param callable $callback
     * @param int $priority
     * @param int $acceptedArgs
     * @return bool
     */
    public function addFilter(string $hookName, callable $callback, int $priority = 10, int $acceptedArgs = 1): bool;

    /**
     * Execute actions attached to a hook
     *
     * @param string $hookName
     * @param mixed ...$args
     * @return void
     */
    public function doAction(string $hookName, mixed ...$args): void;

    /**
     * Apply filters attached to a hook
     *
     * @param string $hookName
     * @param mixed $value
     * @param mixed ...$args
     * @return mixed
     */
    public function applyFilters(string $hookName, mixed $value, mixed ...$args): mixed;

    /**
     * Remove an action
     *
     * @param string $hookName
     * @param callable $callback
     * @param int $priority
     * @return bool
     */
    public function removeAction(string $hookName, callable $callback, int $priority = 10): bool;

    /**
     * Remove a filter
     *
     * @param string $hookName
     * @param callable $callback
     * @param int $priority
     * @return bool
     */
    public function removeFilter(string $hookName, callable $callback, int $priority = 10): bool;
}