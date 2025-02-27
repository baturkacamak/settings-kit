<?php

namespace WPSettingsKit\Builder\Interface;

use WPSettingsKit\Field\Base\Interface\IField;

/**
 * Interface for field builder decorators
 */
interface IFieldBuilderDecorator
{
    /**
     * Apply decorator to configuration
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Updated configuration
     */
    public function applyToConfig(array $config): array;
}