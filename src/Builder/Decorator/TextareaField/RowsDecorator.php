<?php

namespace WPSettingsKit\Builder\Decorator\TextareaField;

use WPSettingsKit\Attribute\FieldDecorator;
use WPSettingsKit\Builder\Decorator\AbstractFieldBuilderDecorator;

/**
 * Decorator for setting the number of rows in a textarea field.
 */
#[FieldDecorator(
    type: 'textarea',
    method: 'setRows',
    priority: 10
)]
class RowsDecorator extends AbstractFieldBuilderDecorator
{
    /**
     * @var int Number of rows
     */
    private int $rows;

    /**
     * Constructor.
     *
     * @param int $rows Number of rows
     * @param int|null $priority Optional priority override
     */
    public function __construct(int $rows, ?int $priority = null)
    {
        parent::__construct($priority);
        $this->rows = max(1, $rows); // Ensure at least 1
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfigModifications(): array
    {
        return [
            'rows' => $this->rows,
            'attributes' => [
                'rows' => $this->rows
            ]
        ];
    }
}