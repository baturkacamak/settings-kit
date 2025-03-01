<?php

namespace WPSettingsKit\Application\Facade;

use WPSettingsKit\Infrastructure\Platform\WordPress\Core\Interface\IHookService;

/**
 * Facade for WordPress hook functions.
 */
class Hook extends Facade {
    /**
     * @inheritDoc
     */
    protected static function getFacadeAccessor(): string {
        return IHookService::class;
    }
}