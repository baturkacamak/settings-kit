<?php

namespace WPSettingsKit\Builder\Decorator\SelectField;

use WPSettingsKit\Attribute\FieldDecorator;
use WPSettingsKit\Builder\Decorator\AbstractFieldBuilderDecorator;

/**
 * Decorator for enabling multiple selection in select fields.
 */
#[FieldDecorator(
    type: 'select',
    method: 'setMultiple',
    priority: 20
)]
class MultipleDecorator extends AbstractFieldBuilderDecorator
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
        parent::__construct($priority);
        $this->multiple = $multiple;
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfigModifications(): array
    {
        $modifications = [
            'multiple' => $this->multiple,
            'attributes' => [
                'multiple' => $this->multiple ? 'multiple' : null
            ]
        ];

        // If enabling multiple, ensure the name attribute ends with [] for array processing
        if ($this->multiple) {
            $modifications['name_suffix'] = '[]';
        }

        return $modifications;
    }

    /**
     * {@inheritdoc}
     */
    public function applyToConfig(array $config): array
    {
        $config = parent::applyToConfig($config);

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