<?php

namespace WPSettingsKit\Domain\Field\Enhancer\BuilderEnhancer\SelectField;

use WPSettingsKit\Domain\Field\Enhancer\Attribute\FieldEnhancer;
use WPSettingsKit\Domain\Field\Enhancer\BuilderEnhancer\AbstractFieldBuilderEnhancer;

/**
 * Enhancer for adding a single option to select fields.
 */
#[FieldEnhancer(
    type: 'select',
    method: 'addOption',
    priority: 11
)]
class SingleOptionEnhancer extends AbstractFieldBuilderEnhancer
{
    /**
     * @var string Option key
     */
    private string $key;

    /**
     * @var string Option label
     */
    private string $label;

    /**
     * Constructor.
     *
     * @param string $key Option key/value
     * @param string $label Option display label
     * @param int|null $priority Optional priority override
     */
    public function __construct(string $key, string $label, ?int $priority = null)
    {
        parent::__construct($priority, 'select');
        $this->key   = $key;
        $this->label = $label;
    }

    /**
     * Apply custom logic for adding a single option.
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Modified configuration
     */
    protected function applyCustomLogic(array $config): array
    {
        // Initialize options array if it doesn't exist
        if (!isset($config['options'])) {
            $config['options'] = [];
        }

        // Add the single option
        $config['options'][$this->key] = $this->label;

        return $config;
    }
}