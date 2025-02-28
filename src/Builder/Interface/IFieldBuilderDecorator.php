<?php

namespace WPSettingsKit\Builder\Interface;

/**
 * Interface for field builder decorators.
 *
 * Defines the contract for classes that decorate field builders by modifying their configuration.
 */
interface IFieldBuilderDecorator
{
    /**
     * Applies the decorator to the field configuration.
     *
     * @param array<string, mixed> $config The current field configuration
     * @return array<string, mixed> The modified field configuration
     */
    public function applyToConfig(array $config): array;

    /**
     * Gets the decorator's priority.
     *
     * Lower numbers run first.
     *
     * @return int The priority value
     */
    public function getPriority(): int;

    /**
     * Gets the field type(s) this decorator applies to.
     *
     * @return string|array<string> The field type(s)
     */
    public function getFieldTypes(): string|array;
}