<?php

namespace WPSettingsKit\Builder\Decorator\TextField;

use WPSettingsKit\Attribute\FieldDecorator;
use WPSettingsKit\Builder\Decorator\AbstractFieldBuilderDecorator;

/**
 * Decorator for setting minimum length on text fields.
 */
#[FieldDecorator(
    type: 'text',
    method: 'setMinLength',
    priority: 11
)]
class MinLengthDecorator extends AbstractFieldBuilderDecorator
{
    /**
     * @var int Minimum character length
     */
    private int $minLength;

    /**
     * Constructor.
     *
     * @param int $minLength Minimum character length
     * @param int|null $priority Optional priority override
     */
    public function __construct(int $minLength, ?int $priority = null)
    {
        parent::__construct($priority);
        $this->minLength = $minLength;
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfigModifications(): array
    {
        return [
            'min_length' => $this->minLength,
            'attributes' => [
                'minlength' => $this->minLength
            ]
        ];
    }
}