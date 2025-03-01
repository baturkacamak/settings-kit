<?php

namespace WPSettingsKit\Domain\Registry;

use;
use ReflectionClass;
use WPSettingsKit\Domain\Field\Enhancer\Attribute\FieldEnhancer;
use WPSettingsKit\Exception\RegistryException;

/**
 * Registry for field enhancers.
 *
 * Handles discovery and registration of field enhancers using PHP 8 attributes.
 */
class EnhancerRegistry
{
    /**
     * @var array<string, array<string, array<int, class-string>>> Registered enhancers by type and method
     */
    private static array $enhancers = [];

    /**
     * @var bool Whether enhancers have been discovered
     */
    private static bool $discovered = false;

    /**
     * Discovers and registers all enhancers in a namespace.
     *
     * @param string $namespace The namespace to search for enhancers
     * @return void
     * @throws RegistryException If discovery fails
     */
    public static function discoverEnhancers(string $namespace): void
    {
        if (self::$discovered) {
            return;
        }

        $classes = self::getClassesInNamespace($namespace);

        foreach ($classes as $class) {
            $reflectionClass = new ReflectionClass($class);
            $attributes      = $reflectionClass->getAttributes(FieldEnhancer::class);

            foreach ($attributes as $attribute) {
                $enhancer = $attribute->newInstance();
                self::registerEnhancer(
                    $enhancer->type,
                    $enhancer->method,
                    $class,
                    $enhancer->priority
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
     * Registers a enhancer class.
     *
     * @param string $type Field type (text, select, checkbox, etc.)
     * @param string $method Builder method name
     * @param string $enhancerClass FQCN of enhancer class
     * @param int $priority Priority (lower numbers run first)
     * @return void
     */
    public static function registerEnhancer(
        string $type,
        string $method,
        string $enhancerClass,
        int    $priority = 10
    ): void
    {
        if (!isset(self::$enhancers[$type])) {
            self::$enhancers[$type] = [];
        }

        if (!isset(self::$enhancers[$type][$method])) {
            self::$enhancers[$type][$method] = [];
        }

        self::$enhancers[$type][$method][$priority] = $enhancerClass;
        ksort(self::$enhancers[$type][$method]);
    }

    /**
     * Gets all enhancers for a field type.
     *
     * @param string $type Field type
     * @return array<string, array<string>> enhancers by method
     */
    public static function getEnhancersForType(string $type): array
    {
        $result = [];

        if (!isset(self::$enhancers[$type])) {
            return $result;
        }

        foreach (self::$enhancers[$type] as $method => $prioritizedClasses) {
            $result[$method] = array_values($prioritizedClasses);
        }

        return $result;
    }

    /**
     * Gets a enhancer for a specific field type and method.
     *
     * @param string $type Field type
     * @param string $method Builder method name
     * @return string|null Enhancer class or null if not found
     */
    public static function getEnhancer(string $type, string $method): ?string
    {
        if (!isset(self::$enhancers[$type][$method])) {
            return null;
        }

        $enhancers = self::$enhancers[$type][$method];
        return reset($enhancers);
    }

    /**
     * Resets the registry (mainly for testing).
     *
     * @return void
     */
    public static function reset(): void
    {
        self::$enhancers  = [];
        self::$discovered = false;
    }
}