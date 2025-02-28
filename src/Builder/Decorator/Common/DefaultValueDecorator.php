<?php

namespace WPSettingsKit\Builder\Decorator\Common;

use WPSettingsKit\Attribute\FieldDecorator;
use WPSettingsKit\Builder\Decorator\AbstractFieldBuilderDecorator;

/**
 * Decorator for setting default values for fields.
 */
#[FieldDecorator(
    type: 'all',
    method: 'setDefaultValue',
    priority: 1
)]
class DefaultValueDecorator extends AbstractFieldBuilderDecorator
{
    /**
     * @var mixed Default value
     */
    private mixed $defaultValue;

    /**
     * Constructor.
     *
     * @param mixed $defaultValue Default value
     * @param int|null $priority Optional priority override
     */
    public function __construct(mixed $defaultValue, ?int $priority = null)
    {
        parent::__construct($priority);
        $this->defaultValue = $defaultValue;
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfigModifications(): array
    {
        return [
            'value' => $this->defaultValue
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function applyToConfig(array $config): array
    {
        // Only set the value if not already set
        if (!isset($config['value'])) {
            $config['value'] = $this->defaultValue;
        }

        return $config;
    }
}