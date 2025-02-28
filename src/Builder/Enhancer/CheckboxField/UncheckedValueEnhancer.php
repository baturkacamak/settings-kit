<?php

namespace WPSettingsKit\Builder\Enhancer\CheckboxField;

use WPSettingsKit\Attribute\FieldEnhancer;
use WPSettingsKit\Builder\Enhancer\AbstractFieldBuilderEnhancer;

/**
 * Enhancer for setting the unchecked value of a checkbox field.
 */
#[FieldEnhancer(
    type: 'checkbox',
    method: 'setUncheckedValue',
    priority: 15
)]
class UncheckedValueEnhancer extends AbstractFieldBuilderEnhancer
{
    /**
     * @var mixed Value when checkbox is unchecked
     */
    private mixed $uncheckedValue;

    /**
     * Constructor.
     *
     * @param mixed $uncheckedValue Value when checkbox is unchecked
     * @param int|null $priority Optional priority override
     */
    public function __construct(mixed $uncheckedValue, ?int $priority = null)
    {
        parent::__construct($priority);
        $this->uncheckedValue = $uncheckedValue;
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfigModifications(): array
    {
        return [
            'unchecked_value' => $this->uncheckedValue,
        ];
    }
}