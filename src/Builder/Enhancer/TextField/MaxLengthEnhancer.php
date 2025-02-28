<?php

namespace WPSettingsKit\Builder\Enhancer\TextField;

use WPSettingsKit\Attribute\FieldEnhancer;
use WPSettingsKit\Builder\Enhancer\AbstractFieldBuilderEnhancer;

/**
 * Enhancer for setting maximum length on text fields.
 */
#[FieldEnhancer(
    type: 'text',
    method: 'setMaxLength',
    priority: 10
)]
class MaxLengthEnhancer extends AbstractFieldBuilderEnhancer
{
    /**
     * @var int Maximum character length
     */
    private int $maxLength;

    /**
     * Constructor.
     *
     * @param int $maxLength Maximum character length
     * @param int|null $priority Optional priority override
     */
    public function __construct(int $maxLength, ?int $priority = null)
    {
        parent::__construct($priority);
        $this->maxLength = $maxLength;
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfigModifications(): array
    {
        return [
            'max_length' => $this->maxLength,
            'attributes' => [
                'maxlength' => $this->maxLength
            ]
        ];
    }
}