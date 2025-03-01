<?php

namespace WPSettingsKit\Domain\Field\Enhancer\BuilderEnhancer\TextField;

use WPSettingsKit\Domain\Field\Enhancer\Attribute\FieldEnhancer;
use WPSettingsKit\Domain\Field\Enhancer\BuilderEnhancer\AbstractFieldBuilderEnhancer;

/**
 * Enhancer for setting minimum length on text fields.
 */
#[FieldEnhancer(
    type: 'text',
    method: 'setMinLength',
    priority: 11
)]
class MinLengthEnhancer extends AbstractFieldBuilderEnhancer
{
    /**
     * @var int Minimum character length
     */
    private int $minLength;

    /**
     * Constructor.
     *
     * @param int $minLength Minimum character length
     * @param int|null $priority Optional priority override
     */
    public function __construct(int $minLength, ?int $priority = null)
    {
        parent::__construct($priority);
        $this->minLength = $minLength;
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfigModifications(): array
    {
        return [
            'min_length' => $this->minLength,
            'attributes' => [
                'minlength' => $this->minLength
            ]
        ];
    }
}