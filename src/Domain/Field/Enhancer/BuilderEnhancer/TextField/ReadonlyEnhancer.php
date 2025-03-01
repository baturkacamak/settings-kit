<?php

namespace WPSettingsKit\Domain\Field\Enhancer\BuilderEnhancer\TextField;

use WPSettingsKit\Domain\Field\Enhancer\Attribute\FieldEnhancer;
use WPSettingsKit\Domain\Field\Enhancer\BuilderEnhancer\AbstractFieldBuilderEnhancer;

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