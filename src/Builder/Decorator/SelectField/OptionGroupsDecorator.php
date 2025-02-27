<?php

namespace WPSettingsKit\Builder\Decorator\SelectField;

use WPSettingsKit\Builder\Interface\IFieldBuilderDecorator;

/**
 * Decorator for setting option groups in select fields
 */
class OptionGroupsDecorator implements IFieldBuilderDecorator
{
    /**
     * @var array<string, array<string, string>> Option groups
     */
    private array $optionGroups;

    /**
     * Constructor
     *
     * @param array<string, array<string, string>> $optionGroups Option groups (group label => [key => value])
     */
    public function __construct(array $optionGroups)
    {
        $this->optionGroups = $optionGroups;
    }

    /**
     * Apply option groups to configuration
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Updated configuration
     */
    public function applyToConfig(array $config): array
    {
        $config['option_groups'] = $this->optionGroups;
        return $config;
    }
}