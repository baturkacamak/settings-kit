<?php

namespace WPSettingsKit\Infrastructure\Platform\WordPress;

use WPSettingsKit\Infrastructure\Platform\WordPress\Interface\IHookManager;

class WPHookManager implements IHookManager
{
    /**
     * @inheritDoc
     */
    public function addAction(string $hookName, callable $callback, int $priority = 10, int $acceptedArgs = 1): bool
    {
        return add_action($hookName, $callback, $priority, $acceptedArgs);
    }

    /**
     * @inheritDoc
     */
    public function addFilter(string $hookName, callable $callback, int $priority = 10, int $acceptedArgs = 1): bool
    {
        return add_filter($hookName, $callback, $priority, $acceptedArgs);
    }

    /**
     * @inheritDoc
     */
    public function doAction(string $hookName, mixed ...$args): void
    {
        do_action($hookName, ...$args);
    }

    /**
     * @inheritDoc
     */
    public function applyFilters(string $hookName, mixed $value, mixed ...$args): mixed
    {
        return apply_filters($hookName, $value, ...$args);
    }

    /**
     * @inheritDoc
     */
    public function removeAction(string $hookName, callable $callback, int $priority = 10): bool
    {
        return remove_action($hookName, $callback, $priority);
    }

    /**
     * @inheritDoc
     */
    public function removeFilter(string $hookName, callable $callback, int $priority = 10): bool
    {
        return remove_filter($hookName, $callback, $priority);
    }
}