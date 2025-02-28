<?php

namespace WPSettingsKit\Builder\Enhancer\TextField;

use WPSettingsKit\Attribute\FieldEnhancer;
use WPSettingsKit\Builder\Enhancer\AbstractFieldBuilderEnhancer;

/**
 * Enhancer for setting the size of text fields.
 */
#[FieldEnhancer(
    type: 'text',
    method: 'setSize',
    priority: 35
)]
class SizeEnhancer extends AbstractFieldBuilderEnhancer
{
    /**
     * @var int Input field size (width in characters)
     */
    private int $size;

    /**
     * Constructor.
     *
     * @param int $size Input field size
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