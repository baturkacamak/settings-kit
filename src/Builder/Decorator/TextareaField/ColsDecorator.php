<?php

namespace WPSettingsKit\Builder\Decorator\TextareaField;

use WPSettingsKit\Attribute\FieldDecorator;
use WPSettingsKit\Builder\Decorator\AbstractFieldBuilderDecorator;

/**
 * Decorator for setting the number of columns in a textarea field.
 */
#[FieldDecorator(
    type: 'textarea',
    method: 'setCols',
    priority: 15
)]
class ColsDecorator extends AbstractFieldBuilderDecorator
{
    /**
     * @var int Number of columns
     */
    private int $cols;

    /**
     * Constructor.
     *
     * @param int $cols Number of columns
     * @param int|null $priority Optional priority override
     */
    public function __construct(int $cols, ?int $priority = null)
    {
        parent::__construct($priority);
        $this->cols = max(1, $cols); // Ensure at least 1
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfigModifications(): array
    {
        return [
            'cols' => $this->cols,
            'attributes' => [
                'cols' => $this->cols
            ]
        ];
    }
}