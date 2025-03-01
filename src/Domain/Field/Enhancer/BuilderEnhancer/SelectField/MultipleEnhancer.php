<?php

namespace WPSettingsKit\Domain\Field\Enhancer\BuilderEnhancer\SelectField;

use WPSettingsKit\Domain\Field\Enhancer\Attribute\FieldEnhancer;
use WPSettingsKit\Domain\Field\Enhancer\BuilderEnhancer\AbstractFieldBuilderEnhancer;

/**
 * Enhancer for enabling multiple selection in select fields.
 */
#[FieldEnhancer(
    type: 'select',
    method: 'setMultiple',
    priority: 20
)]
class MultipleEnhancer extends AbstractFieldBuilderEnhancer
{
    /**
     * @var bool Whether multiple selection is enabled
     */
    private bool $multiple;

    /**
     * Constructor.
     *
     * @param bool $multiple Whether to enable multiple selection
     * @param int|null $priority Optional priority override
     */
    public function __construct(bool $multiple = true, ?int $priority = null)
    {
        parent::__construct($priority, 'select');
        $this->multiple = $multiple;
    }

    /**
     * Get configuration values.
     *
     * @return array<string, mixed> Configuration values
     */
    protected function getConfigValues(): array
    {
        $values = [
            'multiple' => $this->multiple,
        ];

        // If enabling multiple, ensure the name attribute ends with [] for array processing
        if ($this->multiple) {
            $values['name_suffix'] = '[]';
        }

        return $values;
    }

    /**
     * Get attribute values.
     *
     * @return array<string, mixed> Attribute values
     */
    protected function getAttributeValues(): array
    {
        return [
            'multiple' => $this->multiple ? 'multiple' : null,
        ];
    }

    /**
     * Apply custom logic for multiple selection.
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Modified configuration
     */
    protected function applyCustomLogic(array $config): array
    {
        // Ensure default value is an array if multiple is enabled
        if ($this->multiple && isset($config['value']) && !is_array($config['value'])) {
            if (empty($config['value'])) {
                $config['value'] = [];
            } else {
                $config['value'] = [$config['value']];
            }
        }

        return $config;
    }
}