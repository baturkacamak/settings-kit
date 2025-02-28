<?php

namespace WPSettingsKit\Builder\Decorator\CheckboxField;

use WPSettingsKit\Attribute\FieldDecorator;
use WPSettingsKit\Builder\Decorator\AbstractFieldBuilderDecorator;

/**
 * Decorator for setting the checked value of a checkbox field.
 */
#[FieldDecorator(
    type: 'checkbox',
    method: 'setCheckedValue',
    priority: 10
)]
class CheckedValueDecorator extends AbstractFieldBuilderDecorator
{
    /**
     * @var mixed Value when checkbox is checked
     */
    private mixed $checkedValue;

    /**
     * Constructor.
     *
     * @param mixed $checkedValue Value when checkbox is checked
     * @param int|null $priority Optional priority override
     */
    public function __construct(mixed $checkedValue, ?int $priority = null)
    {
        parent::__construct($priority);
        $this->checkedValue = $checkedValue;
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfigModifications(): array
    {
        return [
            'checked_value' => $this->checkedValue,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function applyToConfig(array $config): array
    {
        $config = parent::applyToConfig($config);

        // Modify the HTML value attribute
        if (!isset($config['attributes'])) {
            $config['attributes'] = [];
        }

        $config['attributes']['value'] = $this->checkedValue;

        return $config;
    }
}