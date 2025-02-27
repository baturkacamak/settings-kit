<?php

namespace WPSettingsKit\Builder\Decorator\TextField;

use WPSettingsKit\Builder\Interface\IFieldBuilderDecorator;

/**
 * Decorator for setting readonly attribute on text fields
 */
class ReadonlyDecorator implements IFieldBuilderDecorator
{
    /**
     * @var bool Whether field is readonly
     */
    private bool $readonly;

    /**
     * Constructor
     *
     * @param bool $readonly Whether to make field readonly
     */
    public function __construct(bool $readonly = true)
    {
        $this->readonly = $readonly;
    }

    /**
     * Apply readonly attribute to configuration
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Updated configuration
     */
    public function applyToConfig(array $config): array
    {
        $config['readonly'] = $this->readonly;
        return $config;
    }
}
