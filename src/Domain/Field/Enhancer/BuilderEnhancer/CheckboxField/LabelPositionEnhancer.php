<?php

namespace WPSettingsKit\Domain\Field\Enhancer\BuilderEnhancer\CheckboxField;

use WPSettingsKit\Domain\Field\Enhancer\Attribute\FieldEnhancer;
use WPSettingsKit\Domain\Field\Enhancer\BuilderEnhancer\AbstractFieldBuilderEnhancer;

/**
 * Enhancer for setting the label position of a checkbox field.
 */
#[FieldEnhancer(
    type: 'checkbox',
    method: 'setLabelPosition',
    priority: 20
)]
class LabelPositionEnhancer extends AbstractFieldBuilderEnhancer
{
    /**
     * @var string Label position ('before' or 'after')
     */
    private string $position;

    /**
     * Constructor.
     *
     * @param string $position Label position ('before' or 'after')
     * @param int|null $priority Optional priority override
     */
    public function __construct(string $position, ?int $priority = null)
    {
        parent::__construct($priority);
        $this->position = in_array($position, ['before', 'after']) ? $position : 'after';
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfigModifications(): array
    {
        return [
            'label_position' => $this->position,
        ];
    }
}