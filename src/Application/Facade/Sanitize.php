<?php

namespace WPSettingsKit\Application\Facade;

use WPSettingsKit\Infrastructure\Platform\WordPress\Core\Interface\ISanitizationService;

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