<?php

namespace WPSettingsKit\Builder\Decorator\Common;

use WPSettingsKit\Attribute\FieldDecorator;
use WPSettingsKit\Builder\Decorator\AbstractFieldBuilderDecorator;

/**
 * Decorator for setting disabled state on fields.
 *
 * This decorator can be applied to all field types.
 */
#[FieldDecorator(
    type: 'all',
    method: 'setDisabled',
    priority: 50
)]
class DisabledDecorator extends AbstractFieldBuilderDecorator
{
    /**
     * @var bool Whether the field is disabled
     */
    private bool $disabled;

    /**
     * Constructor.
     *
     * @param bool $disabled Whether to disable the field
     * @param int|null $priority Optional priority override
     */
    public function __construct(bool $disabled = true, ?int $priority = null)
    {
        parent::__construct($priority);
        $this->disabled = $disabled;
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfigModifications(): array
    {
        return [
            'disabled' => $this->disabled,
            'attributes' => [
                'disabled' => $this->disabled ? 'disabled' : null,
            ]
        ];
    }
}