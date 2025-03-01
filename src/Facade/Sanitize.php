<?php

namespace WPSettingsKit\Facade;

use WPSettingsKit\WordPress\Core\Interface\ISanitizationService;

/**
 * Facade for WordPress sanitization functions.
 */
class Sanitize extends Facade {
    /**
     * @inheritDoc
     */
    protected static function getFacadeAccessor(): string {
        return ISanitizationService::class;
    }
}