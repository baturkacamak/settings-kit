<?php

namespace WPSettingsKit\Builder\Enhancer\SelectField;

use WPSettingsKit\Attribute\FieldEnhancer;
use WPSettingsKit\Builder\Enhancer\AbstractFieldBuilderEnhancer;

/**
 * Enhancer for setting the visible number of options in a select field.
 */
#[FieldEnhancer(
    type: 'select',
    method: 'setSize',
    priority: 25
)]
class SelectSizeEnhancer extends AbstractFieldBuilderEnhancer
{
    /**
     * @var int Number of visible options
     */
    private int $size;

    /**
     * Constructor.
     *
     * @param int $size Number of visible options
     * @param int|null $priority Optional priority override
     */
    public function __construct(int $size, ?int $priority = null)
    {
        parent::__construct($priority);
        $this->size = max(1, $size); // Ensure at least 1
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfigModifications(): array
    {
        return [
            'size' => $this->size,
            'attributes' => [
                'size' => $this->size
            ]
        ];
    }
}