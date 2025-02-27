<?php

namespace WPSettingsKit\Builder\Decorator\CheckboxField;

use WPSettingsKit\Builder\Interface\IFieldBuilderDecorator;

/**
 * Decorator for setting label position on checkbox fields
 */
class LabelPositionDecorator implements IFieldBuilderDecorator
{
    /**
     * @var string Label position
     */
    private string $position;

    /**
     * Constructor
     *
     * @param string $position Label position ('before' or 'after')
     */
    public function __construct(string $position)
    {
        $this->position = in_array($position, ['before', 'after']) ? $position : 'after';
    }

    /**
     * Apply label position to configuration
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Updated configuration
     */
    public function applyToConfig(array $config): array
    {
        $config['label_position'] = $this->position;
        return $config;
    }
}