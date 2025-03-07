<?php

namespace WPSettingsKit\Domain\Field\Enhancer\BuilderEnhancer\TextField;

use WPSettingsKit\Domain\Field\Enhancer\Attribute\FieldEnhancer;
use WPSettingsKit\Domain\Field\Enhancer\BuilderEnhancer\AbstractFieldBuilderEnhancer;

/**
 * Enhancer for adding placeholder text to text fields.
 */
#[FieldEnhancer(
    type: 'text',
    method: 'setPlaceholder',
    priority: 20
)]
class PlaceholderEnhancer extends AbstractFieldBuilderEnhancer
{
    /**
     * @var string Placeholder text
     */
    private string $placeholder;

    /**
     * Constructor.
     *
     * @param string $placeholder Placeholder text
     * @param int|null $priority Optional priority override
     */
    public function __construct(string $placeholder, ?int $priority = null)
    {
        parent::__construct($priority);
        $this->placeholder = $placeholder;
    }

    protected function getConfigValues(): array
    {
        return [
            'placeholder' => $this->placeholder,
        ];
    }

    protected function getAttributeValues(): array
    {
        return [
            'placeholder' => $this->placeholder,
        ];
    }
}