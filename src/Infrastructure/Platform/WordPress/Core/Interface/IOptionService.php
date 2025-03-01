<?php

namespace WPSettingsKit\Infrastructure\Platform\WordPress\Core\Interface;

interface IOptionService {
    public function getOption(string $option, mixed $default = false): mixed;
    public function updateOption(string $option, mixed $value, bool $autoload = true): bool;
    public function deleteOption(string $option): bool;
}