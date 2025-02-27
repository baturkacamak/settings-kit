<?php

namespace WPSettingsKit\Builder\Decorator\SelectField;

use WPSettingsKit\Builder\Interface\IFieldBuilderDecorator;

/**
 * Decorator for setting select size (visible items)
 */
class SelectSizeDecorator implements IFieldBuilderDecorator
{
    /**
     * @var int Number of visible items
     */
    private int $size;

    /**
     * Constructor
     *
     * @param int $size Number of visible items
     */
    public function __construct(int $size)
    {
        $this->size = max(1, $size); // Ensure at least 1
    }

    /**
     * Apply select size to configuration
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Updated configuration
     */
    public function applyToConfig(array $config): array
    {
        $config['size'] = $this->size;
        return $config;
    }
}