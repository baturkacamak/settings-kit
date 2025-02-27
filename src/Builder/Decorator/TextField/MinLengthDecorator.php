<?php

namespace WPSettingsKit\Builder\Decorator\TextField;

use WPSettingsKit\Builder\Interface\IFieldBuilderDecorator;

/**
 * Decorator for setting min length on text fields
 */
class MinLengthDecorator implements IFieldBuilderDecorator
{
    /**
     * @var int Minimum length
     */
    private int $minLength;

    /**
     * Constructor
     *
     * @param int $minLength Minimum character length
     */
    public function __construct(int $minLength)
    {
        $this->minLength = $minLength;
    }

    /**
     * Apply min length to configuration
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Updated configuration
     */
    public function applyToConfig(array $config): array
    {
        $config['min_length'] = $this->minLength;
        return $config;
    }
}