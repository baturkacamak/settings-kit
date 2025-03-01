<?php

namespace WPSettingsKit\Infrastructure\Platform\WordPress\Core;

use WPSettingsKit\Infrastructure\Platform\WordPress\Core\Interface\IHookService;

class WPHookService implements IHookService {
    /**
     * @inheritDoc
     */
    public function addAction(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): bool {
        return add_action($tag, $function_to_add, $priority, $accepted_args);
    }

    /**
     * @inheritDoc
     */
    public function doAction(string $tag, mixed ...$args): void {
        do_action($tag, ...$args);
    }

    /**
     * @inheritDoc
     */
    public function addFilter(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): bool {
        return add_filter($tag, $function_to_add, $priority, $accepted_args);
    }

    /**
     * @inheritDoc
     */
    public function applyFilters(string $tag, mixed $value, mixed ...$args): mixed {
        return apply_filters($tag, $value, ...$args);
    }

    /**
     * @inheritDoc
     */
    public function removeAction(string $tag, callable $function_to_remove, int $priority = 10): bool {
        return remove_action($tag, $function_to_remove, $priority);
    }

    /**
     * @inheritDoc
     */
    public function removeFilter(string $tag, callable $function_to_remove, int $priority = 10): bool {
        return remove_filter($tag, $function_to_remove, $priority);
    }

    /**
     * @inheritDoc
     */
    public function hasAction(string $tag, callable $function_to_check = null): bool|int {
        return has_action($tag, $function_to_check);
    }

    /**
     * @inheritDoc
     */
    public function hasFilter(string $tag, callable $function_to_check = null): bool|int {
        return has_filter($tag, $function_to_check);
    }

    /**
     * @inheritDoc
     */
    public function currentFilter(): string|false {
        return current_filter();
    }

    /**
     * @inheritDoc
     */
    public function didAction(string $tag): int {
        return did_action($tag);
    }
}