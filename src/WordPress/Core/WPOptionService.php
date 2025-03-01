<?php

namespace WPSettingsKit\WordPress\Core;

use WPSettingsKit\WordPress\Core\Interface\IOptionService;

class WPOptionService implements IOptionService {
    /**
     * @inheritDoc
     */
    public function getOption(string $option, mixed $default = false): mixed {
        return get_option($option, $default);
    }

    /**
     * @inheritDoc
     */
    public function updateOption(string $option, mixed $value, bool $autoload = true): bool {
        return update_option($option, $value, $autoload);
    }

    /**
     * @inheritDoc
     */
    public function deleteOption(string $option): bool {
        return delete_option($option);
    }
}