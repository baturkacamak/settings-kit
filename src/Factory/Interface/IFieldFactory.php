<?php

namespace  WPSettingsKit\Factory\Interface;


use WPSettingsKit\Field\Base\Interface\IField;
use WPSettingsKit\Interface\IFieldBuilder;

interface IFieldFactory {
    /**
     * Create a field builder for the specified type
     *
     * @param string $type Field type
     * @return IFieldBuilder Builder instance
     * @throws \InvalidArgumentException If field type is not supported
     */
    public function createBuilder(string $type): IFieldBuilder;

    /**
     * Create a field directly from configuration
     *
     * @param string $type Field type
     * @param array $config Field configuration
     * @return IField Field instance
     * @throws \InvalidArgumentException If field type is not supported or config is invalid
     */
    public function createField(string $type, array $config): IField;

    /**
     * Register a new field type
     *
     * @param string $type Field type identifier
     * @param string $builderClass Builder class name
     * @param string $fieldClass Field class name
     * @return void
     * @throws \InvalidArgumentException If builder or field class doesn't exist
     */
    public function registerFieldType(string $type, string $builderClass, string $fieldClass): void;

    /**
     * Check if a field type is supported
     *
     * @param string $type Field type to check
     * @return bool True if supported, false otherwise
     */
    public function supportsFieldType(string $type): bool;

    /**
     * Get all supported field types
     *
     * @return array List of supported field types
     */
    public function getSupportedFieldTypes(): array;
}
