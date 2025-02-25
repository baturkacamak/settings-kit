<?php

namespace  WPSettingsKit\Factory;

use SelectFieldBuilder;
use WPSettingsKit\Builder\CheckboxFieldBuilder;
use WPSettingsKit\Builder\TextFieldBuilder;
use  WPSettingsKit\Builder\Interface\IFieldBuilder;
use  WPSettingsKit\Field\Interface\IField;

/**
 * Factory for creating field builders
 */
class FieldFactory
{
    /**
     * Create a field directly from configuration
     */
    public function createField(string $type, array $config): IField
    {
        $builder = $this->createBuilder($type);

        foreach ($config as $method => $value) {
            $method = 'set' . ucfirst($method);
            if (method_exists($builder, $method)) {
                $builder->$method($value);
            }
        }

        return $builder->build();
    }

    /**
     * Create a field builder
     */
    public function createBuilder(string $type): IFieldBuilder
    {
        return match ($type) {
            'text' => new TextFieldBuilder(),
            'select' => new SelectFieldBuilder(),
            'checkbox' => new CheckboxFieldBuilder(),
            default => throw new \InvalidArgumentException("Unknown field type: $type"),
        };
    }
}