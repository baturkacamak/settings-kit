<?php

namespace WPSettingsKit\WordPress\Core\Interface;

interface IHookService {
    public function addAction(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): bool;
    public function doAction(string $tag, mixed ...$args): void;
    public function addFilter(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): bool;
    public function applyFilters(string $tag, mixed $value, mixed ...$args): mixed;
}