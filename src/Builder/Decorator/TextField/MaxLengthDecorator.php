<?php

namespace WPSettingsKit\Builder\Decorator\TextField;

use WPSettingsKit\Builder\Interface\IFieldBuilderDecorator;

/**
 * Decorator for setting max length on text fields
 */
class MaxLengthDecorator implements IFieldBuilderDecorator
{
    /**
     * @var int Maximum length
     */
    private int $maxLength;

    /**
     * Constructor
     *
     * @param int $maxLength Maximum character length
     */
    public function __construct(int $maxLength)
    {
        $this->maxLength = $maxLength;
    }

    /**
     * Apply max length to configuration
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Updated configuration
     */
    public function applyToConfig(array $config): array
    {
        $config['max_length'] = $this->maxLength;
        return $config;
    }
}