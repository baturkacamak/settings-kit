<?php

namespace WPSettingsKit\Application\Facade;

use WPSettingsKit\Infrastructure\Platform\WordPress\Core\Interface\IUserService;

/**
 * Facade for WordPress user functions.
 */
class User extends Facade {
    /**
     * @inheritDoc
     */
    protected static function getFacadeAccessor(): string {
        return IUserService::class;
    }
}