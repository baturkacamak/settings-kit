<?php

namespace WPSettingsKit\Builder\Enhancer\TextField;

use WPSettingsKit\Attribute\FieldEnhancer;
use WPSettingsKit\Builder\Enhancer\AbstractFieldBuilderEnhancer;

/**
 * Enhancer for setting readonly state on text fields.
 */
#[FieldEnhancer(
    type: ['text', 'textarea'],
    method: 'setReadonly',
    priority: 50
)]
class ReadonlyEnhancer extends AbstractFieldBuilderEnhancer
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