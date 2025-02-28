<?php

namespace WPSettingsKit\Builder\Decorator\SelectField;

use WPSettingsKit\Attribute\FieldDecorator;
use WPSettingsKit\Builder\Decorator\AbstractFieldBuilderDecorator;

/**
 * Decorator for adding option groups to select fields.
 */
#[FieldDecorator(
    type: 'select',
    method: 'setOptionGroups',
    priority: 15
)]
class OptionGroupsDecorator extends AbstractFieldBuilderDecorator
{
    /**
     * @var array<string, array<string, string>> Option groups (group label => [key => value])
     */
    private array $optionGroups;

    /**
     * @var bool Whether to replace existing option groups
     */
    private bool $replace;

    /**
     * Constructor.
     *
     * @param array<string, array<string, string>> $optionGroups Option groups
     * @param bool $replace Whether to replace existing option groups
     * @param int|null $priority Optional priority override
     */
    public function __construct(array $optionGroups, bool $replace = true, ?int $priority = null)
    {
        parent::__construct($priority);
        $this->optionGroups = $optionGroups;
        $this->replace = $replace;
    }

    /**
     * {@inheritdoc}
     */
    public function applyToConfig(array $config): array
    {
        // Merge with existing option groups if not replacing
        if (!$this->replace && isset($config['option_groups']) && is_array($config['option_groups'])) {
            $config['option_groups'] = array_merge($config['option_groups'], $this->optionGroups);
        } else {
            $config['option_groups'] = $this->optionGroups;
        }

        return $config;
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfigModifications(): array
    {
        // This is not used in the OptionGroupsDecorator since it requires
        // custom handling in applyToConfig
        return [];
    }
}