<?php

namespace WPSettingsKit\Builder\Enhancer;

use ReflectionClass;
use WPSettingsKit\Attribute\FieldEnhancer;
use WPSettingsKit\Builder\Interface\IFieldBuilderEnhancer;

/**
 * Abstract base class for field builder enhancers.
 *
 * Provides common functionality for enhancers, including priority management,
 * field type determination from attributes, and standard config application.
 */
abstract class AbstractFieldBuilderEnhancer implements IFieldBuilderEnhancer
{
    /**
     * @var int The enhancer's priority (lower numbers run first)
     */
    protected int $priority;

    /**
     * @var string|array<string> The field type(s) this enhancer applies to
     */
    protected string|array $fieldTypes;

    /**
     * Constructor.
     *
     * Initializes priority and field types from the Fieldenhancer attribute
     * if not explicitly provided.
     *
     * @param int|null $priority Optional priority override (lower runs first)
     * @param string|array<string>|null $fieldTypes Optional field types override
     */
    public function __construct(?int $priority = null, string|array|null $fieldTypes = null)
    {
        $this->priority   = $priority ?? $this->getPriorityFromAttribute();
        $this->fieldTypes = $fieldTypes ?? $this->getFieldTypesFromAttribute();
    }

    /**
     * Gets the priority from the Fieldenhancer attribute.
     *
     * @return int The priority value (defaults to 10)
     */
    protected function getPriorityFromAttribute(): int
    {
        $reflectionClass = new ReflectionClass($this);
        $attributes      = $reflectionClass->getAttributes(FieldEnhancer::class);

        if (empty($attributes)) {
            return 10;
        }

        $attribute = $attributes[0]->newInstance();
        return $attribute->priority;
    }

    /**
     * Gets the field types from the Fieldenhancer attribute.
     *
     * @return string|array<string> The field type(s) (defaults to 'all')
     */
    protected function getFieldTypesFromAttribute(): string|array
    {
        $reflectionClass = new ReflectionClass($this);
        $attributes      = $reflectionClass->getAttributes(FieldEnhancer::class);

        if (empty($attributes)) {
            return 'all';
        }

        $attribute = $attributes[0]->newInstance();
        return $attribute->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldTypes(): string|array
    {
        return $this->fieldTypes;
    }

    /**
     * Applies this enhancer to a field configuration.
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Modified configuration
     */
    public function applyToConfig(array $config): array
    {
        // Apply direct config modifications
        $config = $this->applyConfigValues($config);

        // Apply attribute modifications
        $config = $this->applyAttributeValues($config);

        // Apply any additional modifications
        $config = $this->applyCustomLogic($config);

        return $config;
    }

    /**
     * Apply direct configuration values.
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Modified configuration
     */
    protected function applyConfigValues(array $config): array
    {
        foreach ($this->getConfigValues() as $key => $value) {
            // Skip attributes, they're handled separately
            if ($key !== 'attributes') {
                $config[$key] = $value;
            }
        }

        return $config;
    }

    /**
     * Gets direct configuration values to apply.
     *
     * @return array<string, mixed> Configuration values
     */
    protected function getConfigValues(): array
    {
        return [];
    }

    /**
     * Apply attribute values.
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Modified configuration
     */
    protected function applyAttributeValues(array $config): array
    {
        $attributes = $this->getAttributeValues();

        if (!empty($attributes)) {
            if (!isset($config['attributes'])) {
                $config['attributes'] = [];
            }

            foreach ($attributes as $attrName => $attrValue) {
                if ($attrValue === null) {
                    unset($config['attributes'][$attrName]);
                } elseif ($attrValue === true) {
                    $config['attributes'][$attrName] = $attrName;
                } else {
                    $config['attributes'][$attrName] = $attrValue;
                }
            }
        }

        return $config;
    }

    /**
     * Gets attribute values to apply.
     *
     * @return array<string, mixed> Attribute values
     */
    protected function getAttributeValues(): array
    {
        return [];
    }

    /**
     * Apply any custom logic beyond simple key-value pairs.
     * Default implementation does nothing.
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Modified configuration
     */
    protected function applyCustomLogic(array $config): array
    {
        return $config;
    }
}