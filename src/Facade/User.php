<?php

namespace WPSettingsKit\Facade;

use WPSettingsKit\WordPress\Core\Interface\IUserService;

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