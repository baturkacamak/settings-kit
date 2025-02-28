<?php

namespace WPSettingsKit\Builder\Enhancer\TextareaField;

use WPSettingsKit\Attribute\FieldEnhancer;
use WPSettingsKit\Builder\Enhancer\AbstractFieldBuilderEnhancer;

/**
 * Enhancer for setting the number of rows in a textarea field.
 */
#[FieldEnhancer(
    type: 'textarea',
    method: 'setRows',
    priority: 10
)]
class RowsEnhancer extends AbstractFieldBuilderEnhancer
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