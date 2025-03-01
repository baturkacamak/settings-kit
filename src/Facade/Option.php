<?php

namespace WPSettingsKit\Facade;

use WPSettingsKit\WordPress\Core\Interface\IOptionService;

/**
 * Facade for WordPress option functions.
 */
class Option extends Facade
{
    /**
     * @inheritDoc
     */
    protected static function getFacadeAccessor(): string
    {
        return IOptionService::class;
    }
}