<?php
/**
 * Factory for creating Settings Manager instances with proper dependency injection.
 *
 * @package WPSettingsKit\Factory
 */

namespace WPSettingsKit\Application\Factory;

use WPSettingsKit\Application\Service\Interface\ISettingsManager;
use WPSettingsKit\Application\Service\SettingsManager;
use WPSettingsKit\Domain\Event\EventManager;
use WPSettingsKit\Infrastructure\Cache\WPSettingsCache;
use WPSettingsKit\Infrastructure\Context\WPContextManager;
use WPSettingsKit\Infrastructure\Permission\WPPermissionManager;
use WPSettingsKit\Infrastructure\Platform\WordPress\WPSettingsRepository;
use WPSettingsKit\Infrastructure\Template\WPTemplateManager;

/**
 * Factory class for creating Settings Manager with all dependencies.
 */
class SettingsManagerFactory
{
    /**
     * Creates a fully configured Settings Manager with all dependencies.
     *
     * @param string $prefix Prefix for all setting keys in the database
     * @param bool $autoload Whether to autoload settings
     * @param string $capability WordPress capability required to manage settings
     * @return ISettingsManager The configured settings manager
     */
    public static function create(
        string $prefix = 'my_settings_',
        bool   $autoload = true,
        string $capability = 'manage_options'
    ): ISettingsManager
    {
        // Create the repository for data storage
        $repository = new WPSettingsRepository($prefix, $autoload);

        // Create cache handler
        $cache = new WPSettingsCache($prefix);

        // Create event dispatcher for field events
        $eventDispatcher = new EventManager();

        // Create permission manager for access control
        $permissionManager = new WPPermissionManager($capability);

        // Create context manager for contextual settings
        $contextManager = new WPContextManager();

        // Create template manager for saving/loading templates
        $templateManager = new WPTemplateManager($prefix . 'templates');

        // Assemble and return the fully configured manager
        return new SettingsManager(
            $repository,
            $cache,
            $eventDispatcher,
            $permissionManager,
            $contextManager,
            $templateManager
        );
    }
}