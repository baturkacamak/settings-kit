<?php

namespace WPSettingsKit\Builder\Decorator\TextField;

use WPSettingsKit\Attribute\FieldDecorator;
use WPSettingsKit\Builder\Decorator\AbstractFieldBuilderDecorator;

/**
 * Decorator for setting readonly state on text fields.
 */
#[FieldDecorator(
    type: ['text', 'textarea'],
    method: 'setReadonly',
    priority: 50
)]
class ReadonlyDecorator extends AbstractFieldBuilderDecorator
{
    /**
     * @var bool Whether the field is readonly
     */
    private bool $readonly;

    /**
     * Constructor.
     *
     * @param bool $readonly Whether to make the field readonly
     * @param int|null $priority Optional priority override
     */
    public function __construct(bool $readonly = true, ?int $priority = null)
    {
        parent::__construct($priority);
        $this->readonly = $readonly;
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfigModifications(): array
    {
        return [
            'readonly' => $this->readonly,
            'attributes' => [
                'readonly' => $this->readonly ? 'readonly' : null,
            ]
        ];
    }
}