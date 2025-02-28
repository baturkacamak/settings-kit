<?php

namespace WPSettingsKit\Builder\Decorator\TextField;

use WPSettingsKit\Attribute\FieldDecorator;
use WPSettingsKit\Builder\Decorator\AbstractFieldBuilderDecorator;

/**
 * Decorator for setting autocomplete values on text fields.
 */
#[FieldDecorator(
    type: 'text',
    method: 'setAutocomplete',
    priority: 30
)]
class AutocompleteDecorator extends AbstractFieldBuilderDecorator
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