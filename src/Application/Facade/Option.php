<?php

namespace WPSettingsKit\Application\Facade;

use WPSettingsKit\Infrastructure\Platform\WordPress\Core\Interface\IOptionService;

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