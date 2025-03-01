<?php

namespace WPSettingsKit\Domain\Field\Enhancer\BuilderEnhancer\SelectField;

use WPSettingsKit\Domain\Field\Enhancer\Attribute\FieldEnhancer;
use WPSettingsKit\Domain\Field\Enhancer\BuilderEnhancer\AbstractFieldBuilderEnhancer;

/**
 * Enhancer for adding option groups to select fields.
 */
#[FieldEnhancer(
    type: 'select',
    method: 'setOptionGroups',
    priority: 15
)]
class OptionGroupsEnhancer extends AbstractFieldBuilderEnhancer {
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
    public function __construct(array $optionGroups, bool $replace = true, ?int $priority = null) {
        parent::__construct($priority, 'select');
        $this->optionGroups = $optionGroups;
        $this->replace = $replace;
    }

    /**
     * Apply custom logic for option groups.
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Modified configuration
     */
    protected function applyCustomLogic(array $config): array {
        // Merge with existing option groups if not replacing
        if (!$this->replace && isset($config['option_groups']) && is_array($config['option_groups'])) {
            $config['option_groups'] = array_merge($config['option_groups'], $this->optionGroups);
        } else {
            $config['option_groups'] = $this->optionGroups;
        }

        return $config;
    }
}