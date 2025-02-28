<?php

namespace WPSettingsKit\Builder\Decorator;

use ReflectionClass;
use WPSettingsKit\Attribute\FieldDecorator;
use WPSettingsKit\Builder\Interface\IFieldBuilderDecorator;

/**
 * Abstract base class for field builder decorators.
 *
 * Provides common functionality for decorators, including priority management,
 * field type determination from attributes, and standard config application.
 */
abstract class AbstractFieldBuilderDecorator implements IFieldBuilderDecorator
{
    /**
     * @var int The decorator's priority (lower numbers run first)
     */
    protected int $priority;

    /**
     * @var string|array<string> The field type(s) this decorator applies to
     */
    protected string|array $fieldTypes;

    /**
     * Constructor.
     *
     * Initializes priority and field types from the FieldDecorator attribute
     * if not explicitly provided.
     *
     * @param int|null $priority Optional priority override (lower runs first)
     * @param string|array<string>|null $fieldTypes Optional field types override
     */
    public function __construct(?int $priority = null, string|array|null $fieldTypes = null)
    {
        $this->priority = $priority ?? $this->getPriorityFromAttribute();
        $this->fieldTypes = $fieldTypes ?? $this->getFieldTypesFromAttribute();
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
     * {@inheritdoc}
     *
     * Standard implementation that modifies the config array based on decorator-specific values.
     * Subclasses should override getConfigModifications() instead of this method.
     */
    public function applyToConfig(array $config): array
    {
        $modifications = $this->getConfigModifications();

        // Apply primary config values
        foreach ($modifications as $key => $value) {
            if ($key !== 'attributes') {
                $config[$key] = $value;
            }
        }

        // Handle attributes specially for proper merging
        if (isset($modifications['attributes']) && is_array($modifications['attributes'])) {
            if (!isset($config['attributes'])) {
                $config['attributes'] = [];
            }

            foreach ($modifications['attributes'] as $attrName => $attrValue) {
                if ($attrValue === null) {
                    unset($config['attributes'][$attrName]);
                } else if ($attrValue === true) {
                    $config['attributes'][$attrName] = $attrName;
                } else {
                    $config['attributes'][$attrName] = $attrValue;
                }
            }
        }

        return $config;
    }

    /**
     * Gets config modifications specific to this decorator.
     *
     * Subclasses should implement this method instead of overriding applyToConfig.
     *
     * @return array<string, mixed> Config modifications to apply
     */
    abstract protected function getConfigModifications(): array;

    /**
     * Gets the priority from the FieldDecorator attribute.
     *
     * @return int The priority value (defaults to 10)
     */
    protected function getPriorityFromAttribute(): int
    {
        $reflectionClass = new ReflectionClass($this);
        $attributes = $reflectionClass->getAttributes(FieldDecorator::class);

        if (empty($attributes)) {
            return 10;
        }

        $attribute = $attributes[0]->newInstance();
        return $attribute->priority;
    }

    /**
     * Gets the field types from the FieldDecorator attribute.
     *
     * @return string|array<string> The field type(s) (defaults to 'all')
     */
    protected function getFieldTypesFromAttribute(): string|array
    {
        $reflectionClass = new ReflectionClass($this);
        $attributes = $reflectionClass->getAttributes(FieldDecorator::class);

        if (empty($attributes)) {
            return 'all';
        }

        $attribute = $attributes[0]->newInstance();
        return $attribute->type;
    }
}