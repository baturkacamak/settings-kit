<?php

namespace WPSettingsKit\Registry;

use ReflectionClass;
use WPSettingsKit\Attribute\ValidationRule;
use WPSettingsKit\Exception\RegistryException;

/**
 * Registry for validation rules.
 *
 * Handles discovery and registration of validation rules using PHP 8 attributes.
 */
class ValidationRuleRegistry
{
    /**
     * @var array<string, array<string, array<int, class-string>>> Registered validation rules by type and method
     */
    private static array $validationRules = [];

    /**
     * @var bool Whether validation rules have been discovered
     */
    private static bool $discovered = false;

    /**
     * Discovers and registers all validation rules in a namespace.
     *
     * @param string $namespace The namespace to search for validation rules
     * @return void
     * @throws RegistryException If discovery fails
     */
    public static function discoverValidationRules(string $namespace): void
    {
        if (self::$discovered) {
            return;
        }

        $classes = self::getClassesInNamespace($namespace);

        foreach ($classes as $class) {
            $reflectionClass = new ReflectionClass($class);
            $attributes = $reflectionClass->getAttributes(ValidationRule::class);

            foreach ($attributes as $attribute) {
                $validationRule = $attribute->newInstance();
                $types = is_array($validationRule->type)
                    ? $validationRule->type
                    : [$validationRule->type];

                foreach ($types as $type) {
                    self::registerValidationRule(
                        $type,
                        $validationRule->method,
                        $class,
                        $validationRule->priority
                    );
                }
            }
        }

        self::$discovered = true;
    }

    /**
     * Registers a validation rule class.
     *
     * @param string $type Field type (text, select, checkbox, etc.)
     * @param string $method Builder method name
     * @param string $validationRuleClass FQCN of validation rule class
     * @param int $priority Priority (lower numbers run first)
     * @return void
     */
    public static function registerValidationRule(
        string $type,
        string $method,
        string $validationRuleClass,
        int $priority = 10
    ): void {
        if (!isset(self::$validationRules[$type])) {
            self::$validationRules[$type] = [];
        }

        if (!isset(self::$validationRules[$type][$method])) {
            self::$validationRules[$type][$method] = [];
        }

        self::$validationRules[$type][$method][$priority] = $validationRuleClass;
        ksort(self::$validationRules[$type][$method]);
    }

    /**
     * Gets all validation rules for a field type.
     *
     * @param string $type Field type
     * @return array<string, array<string>> Validation rules by method
     */
    public static function getValidationRulesForType(string $type): array
    {
        $result = [];

        if (!isset(self::$validationRules[$type])) {
            return $result;
        }

        foreach (self::$validationRules[$type] as $method => $prioritizedClasses) {
            $result[$method] = array_values($prioritizedClasses);
        }

        return $result;
    }

    /**
     * Gets a validation rule for a specific field type and method.
     *
     * @param string $type Field type
     * @param string $method Builder method name
     * @return string|null Validation rule class or null if not found
     */
    public static function getValidationRule(string $type, string $method): ?string
    {
        if (!isset(self::$validationRules[$type][$method])) {
            return null;
        }

        $rules = self::$validationRules[$type][$method];
        return reset($rules);
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

        $classes = [];
        $namespace = trim($namespace, '\\') . '\\';

        foreach ($composerClassMap as $class => $path) {
            if (strpos($class, $namespace) === 0) {
                $classes[] = $class;
            }
        }

        return $classes;
    }

    /**
     * Resets the registry (mainly for testing).
     *
     * @return void
     */
    public static function reset(): void
    {
        self::$validationRules = [];
        self::$discovered = false;
    }
}