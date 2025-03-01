<?php

namespace WPSettingsKit\Domain\Field\Enhancer\BuilderEnhancer\TextField;

use WPSettingsKit\Domain\Field\Enhancer\Attribute\FieldEnhancer;
use WPSettingsKit\Domain\Field\Enhancer\BuilderEnhancer\AbstractFieldBuilderEnhancer;

/**
 * Enhancer for setting autocomplete values on text fields.
 */
#[FieldEnhancer(
    type: 'text',
    method: 'setAutocomplete',
    priority: 30
)]
class AutocompleteEnhancer extends AbstractFieldBuilderEnhancer
{
    /**
     * @var string Autocomplete value (e.g., 'name', 'email', 'off')
     */
    private string $autocomplete;

    /**
     * Constructor.
     *
     * @param string $autocomplete Autocomplete value
     * @param int|null $priority Optional priority override
     */
    public function __construct(string $autocomplete, ?int $priority = null)
    {
        parent::__construct($priority);
        $this->autocomplete = $autocomplete;
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfigModifications(): array
    {
        return [
            'autocomplete' => $this->autocomplete,
            'attributes' => [
                'autocomplete' => $this->autocomplete
            ]
        ];
    }
}