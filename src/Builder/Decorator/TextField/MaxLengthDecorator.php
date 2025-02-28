<?php

namespace WPSettingsKit\Builder\Decorator\TextField;

use WPSettingsKit\Attribute\FieldDecorator;
use WPSettingsKit\Builder\Decorator\AbstractFieldBuilderDecorator;

/**
 * Decorator for setting maximum length on text fields.
 */
#[FieldDecorator(
    type: 'text',
    method: 'setMaxLength',
    priority: 10
)]
class MaxLengthDecorator extends AbstractFieldBuilderDecorator
{
    /**
     * @var int Maximum character length
     */
    private int $maxLength;

    /**
     * Constructor.
     *
     * @param int $maxLength Maximum character length
     * @param int|null $priority Optional priority override
     */
    public function __construct(int $maxLength, ?int $priority = null)
    {
        parent::__construct($priority);
        $this->maxLength = $maxLength;
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfigModifications(): array
    {
        return [
            'max_length' => $this->maxLength,
            'attributes' => [
                'maxlength' => $this->maxLength
            ]
        ];
    }
}