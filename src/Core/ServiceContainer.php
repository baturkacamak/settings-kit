<?php

namespace WPSettingsKit\Core;

use RuntimeException;
use WPSettingsKit\Infrastructure\Platform\WordPress\Core\Interface\IHookService;
use WPSettingsKit\Infrastructure\Platform\WordPress\Core\Interface\IOptionService;
use WPSettingsKit\Infrastructure\Platform\WordPress\Core\Interface\ISanitizationService;
use WPSettingsKit\Infrastructure\Platform\WordPress\Core\Interface\IUserService;
use WPSettingsKit\Infrastructure\Platform\WordPress\Core\WPHookService;
use WPSettingsKit\Infrastructure\Platform\WordPress\Core\WPOptionService;
use WPSettingsKit\Infrastructure\Platform\WordPress\Core\WPSanitizationService;
use WPSettingsKit\Infrastructure\Platform\WordPress\Core\WPUserService;

/**
 * Service container for dependency injection.
 */
class ServiceContainer
{
    /**
     * @var array<string, object> Registered services
     */
    private static array $services = [];

    /**
     * @var array<string, string> Service bindings (interface => implementation)
     */
    private static array $bindings = [];

    /**
     * @var array<string, callable> Factory methods for lazy loading
     */
    private static array $factories = [];

    /**
     * Registers a singleton service.
     *
     * @param string $interface Service interface
     * @param object $implementation Service implementation
     */
    public static function singleton(string $interface, object $implementation): void
    {
        self::$services[$interface] = $implementation;
    }

    /**
     * Registers a factory method for creating a service.
     *
     * @param string $interface Service interface
     * @param callable $factory Factory method
     */
    public static function factory(string $interface, callable $factory): void
    {
        self::$factories[$interface] = $factory;
    }

    /**
     * Resolves a service from the container.
     *
     * @param string $interface Service interface
     * @return object Service implementation
     * @throws RuntimeException If service cannot be resolved
     */
    public static function resolve(string $interface): object
    {
        // Return existing singleton if registered
        if (isset(self::$services[$interface])) {
            return self::$services[$interface];
        }

        // Use factory if registered
        if (isset(self::$factories[$interface])) {
            $instance                   = call_user_func(self::$factories[$interface]);
            self::$services[$interface] = $instance; // Cache as singleton
            return $instance;
        }

        // Create from binding if registered
        if (isset(self::$bindings[$interface])) {
            $implementation             = self::$bindings[$interface];
            $instance                   = new $implementation();
            self::$services[$interface] = $instance; // Cache as singleton
            return $instance;
        }

        throw new RuntimeException("Cannot resolve service: {$interface}");
    }

    /**
     * Checks if a service is registered.
     *
     * @param string $interface Service interface
     * @return bool Whether service is registered
     */
    public static function has(string $interface): bool
    {
        return isset(self::$services[$interface])
            || isset(self::$factories[$interface])
            || isset(self::$bindings[$interface]);
    }

    /**
     * Resets all registered services, bindings and factories.
     */
    public static function reset(): void
    {
        self::$services  = [];
        self::$bindings  = [];
        self::$factories = [];
    }

    /**
     * Boots the core services.
     */
    public static function bootCoreServices(): void
    {
        // Register core service bindings
        self::bind(IOptionService::class, WPOptionService::class);
        self::bind(IHookService::class, WPHookService::class);
        self::bind(ISanitizationService::class, WPSanitizationService::class);
        self::bind(IUserService::class, WPUserService::class);
    }

    /**
     * Binds an interface to an implementation.
     *
     * @param string $interface Interface name
     * @param string $implementation Implementation class name
     */
    public static function bind(string $interface, string $implementation): void
    {
        self::$bindings[$interface] = $implementation;
    }
}