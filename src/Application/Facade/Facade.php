<?php

namespace WPSettingsKit\Application\Facade;

use WPSettingsKit\Core\ServiceContainer;

/**
 * Base facade class.
 */
abstract class Facade
{
    /**
     * Handle dynamic, static calls to the facade.
     *
     * @param string $method Method name
     * @param array $args Method arguments
     * @return mixed
     */
    public static function __callStatic(string $method, array $args)
    {
        $instance = static::getFacadeRoot();
        return $instance->$method(...$args);
    }

    /**
     * Get the registered service for this facade.
     *
     * @return object
     */
    protected static function getFacadeRoot(): object
    {
        $name = static::getFacadeAccessor();
        return ServiceContainer::resolve($name);
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    abstract protected static function getFacadeAccessor(): string;
}