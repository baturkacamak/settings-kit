<?php

namespace WPSettingsKit\Builder\Enhancer\Common;

use WPSettingsKit\Attribute\FieldEnhancer;
use WPSettingsKit\Builder\Enhancer\AbstractFieldBuilderEnhancer;

/**
 * Enhancer for setting disabled state on fields.
 *
 * This enhancer can be applied to all field types.
 */
#[FieldEnhancer(
    type: 'all',
    method: 'setDisabled',
    priority: 50
)]
class DisabledEnhancer extends AbstractFieldBuilderEnhancer
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