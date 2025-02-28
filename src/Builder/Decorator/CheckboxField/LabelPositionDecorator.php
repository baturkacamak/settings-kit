<?php

namespace WPSettingsKit\Builder\Decorator\CheckboxField;

use WPSettingsKit\Attribute\FieldDecorator;
use WPSettingsKit\Builder\Decorator\AbstractFieldBuilderDecorator;

/**
 * Decorator for setting the label position of a checkbox field.
 */
#[FieldDecorator(
    type: 'checkbox',
    method: 'setLabelPosition',
    priority: 20
)]
class LabelPositionDecorator extends AbstractFieldBuilderDecorator
{
    /**
     * @var string Label position ('before' or 'after')
     */
    private string $position;

    /**
     * Constructor.
     *
     * @param string $position Label position ('before' or 'after')
     * @param int|null $priority Optional priority override
     */
    public function __construct(string $position, ?int $priority = null)
    {
        parent::__construct($priority);
        $this->position = in_array($position, ['before', 'after']) ? $position : 'after';
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfigModifications(): array
    {
        return [
            'label_position' => $this->position,
        ];
    }
}