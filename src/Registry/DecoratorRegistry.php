<?php

namespace WPSettingsKit\Registry;

use ReflectionClass;
use WPSettingsKit\Attribute\FieldDecorator;
use WPSettingsKit\Exception\RegistryException;

/**
 * Registry for field decorators.
 *
 * Handles discovery and registration of field decorators using PHP 8 attributes.
 */
class DecoratorRegistry
{
    /**
     * @var array<string, array<string, array<int, class-string>>> Registered decorators by type and method
     */
    private static array $decorators = [];

    /**
     * @var bool Whether decorators have been discovered
     */
    private static bool $discovered = false;

    /**
     * Discovers and registers all decorators in a namespace.
     *
     * @param string $namespace The namespace to search for decorators
     * @return void
     * @throws RegistryException If discovery fails
     */
    public static function discoverDecorators(string $namespace): void
    {
        if (self::$discovered) {
            return;
        }

        $classes = self::getClassesInNamespace($namespace);

        foreach ($classes as $class) {
            $reflectionClass = new ReflectionClass($class);
            $attributes      = $reflectionClass->getAttributes(FieldDecorator::class);

            foreach ($attributes as $attribute) {
                $decorator = $attribute->newInstance();
                self::registerDecorator(
                    $decorator->type,
                    $decorator->method,
                    $class,
                    $decorator->priority
                );
            }
        }

        self::$discovered = true;
    }

    /**
     * Gets classes in a namespace using Composer's classmap.
     *
     * @param string $namespace Namespace to search
     * @return array<string> Array of class names
     * @throws RegistryException If classmap cannot be loaded
     */
    private static function getClassesInNamespace(string $namespace): array
    {
        $composerClassMap = require_once(dirname(ABSPATH) . '/vendor/composer/autoload_classmap.php');

        if (!is_array($composerClassMap)) {
            throw new RegistryException('Failed to load Composer classmap');
        }

        $classes   = [];
        $namespace = trim($namespace, '\\') . '\\';

        foreach ($composerClassMap as $class => $path) {
            if (strpos($class, $namespace) === 0) {
                $classes[] = $class;
            }
        }

        return $classes;
    }

    /**
     * Registers a decorator class.
     *
     * @param string $type Field type (text, select, checkbox, etc.)
     * @param string $method Builder method name
     * @param string $decoratorClass FQCN of decorator class
     * @param int $priority Priority (lower numbers run first)
     * @return void
     */
    public static function registerDecorator(
        string $type,
        string $method,
        string $decoratorClass,
        int    $priority = 10
    ): void
    {
        if (!isset(self::$decorators[$type])) {
            self::$decorators[$type] = [];
        }

        if (!isset(self::$decorators[$type][$method])) {
            self::$decorators[$type][$method] = [];
        }

        self::$decorators[$type][$method][$priority] = $decoratorClass;
        ksort(self::$decorators[$type][$method]);
    }

    /**
     * Gets all decorators for a field type.
     *
     * @param string $type Field type
     * @return array<string, array<string>> Decorators by method
     */
    public static function getDecoratorsForType(string $type): array
    {
        $result = [];

        if (!isset(self::$decorators[$type])) {
            return $result;
        }

        foreach (self::$decorators[$type] as $method => $prioritizedClasses) {
            $result[$method] = array_values($prioritizedClasses);
        }

        return $result;
    }

    /**
     * Gets a decorator for a specific field type and method.
     *
     * @param string $type Field type
     * @param string $method Builder method name
     * @return string|null Decorator class or null if not found
     */
    public static function getDecorator(string $type, string $method): ?string
    {
        if (!isset(self::$decorators[$type][$method])) {
            return null;
        }

        $decorators = self::$decorators[$type][$method];
        return reset($decorators);
    }

    /**
     * Resets the registry (mainly for testing).
     *
     * @return void
     */
    public static function reset(): void
    {
        self::$decorators = [];
        self::$discovered = false;
    }
}