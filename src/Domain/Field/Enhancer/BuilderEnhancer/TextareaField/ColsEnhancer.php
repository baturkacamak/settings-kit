<?php

namespace WPSettingsKit\Domain\Field\Enhancer\BuilderEnhancer\TextareaField;

use WPSettingsKit\Domain\Field\Enhancer\Attribute\FieldEnhancer;
use WPSettingsKit\Domain\Field\Enhancer\BuilderEnhancer\AbstractFieldBuilderEnhancer;

/**
 * Enhancer for setting the number of columns in a textarea field.
 */
#[FieldEnhancer(
    type: 'textarea',
    method: 'setCols',
    priority: 15
)]
class ColsEnhancer extends AbstractFieldBuilderEnhancer
{
    /**
     * @var int Number of columns
     */
    private int $cols;

    /**
     * Constructor.
     *
     * @param int $cols Number of columns
     * @param int|null $priority Optional priority override
     */
    public function __construct(int $cols, ?int $priority = null)
    {
        parent::__construct($priority);
        $this->cols = max(1, $cols); // Ensure at least 1
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfigModifications(): array
    {
        return [
            'cols' => $this->cols,
            'attributes' => [
                'cols' => $this->cols
            ]
        ];
    }
}