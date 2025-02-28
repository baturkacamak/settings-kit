<?php

namespace WPSettingsKit\Builder\Decorator\Common;

use WPSettingsKit\Attribute\FieldDecorator;
use WPSettingsKit\Builder\Decorator\AbstractFieldBuilderDecorator;

/**
 * Decorator for setting required state on fields.
 *
 * This decorator can be applied to all field types.
 */
#[FieldDecorator(
    type: 'all',
    method: 'setRequired',
    priority: 5
)]
class RequiredDecorator extends AbstractFieldBuilderDecorator
{
    /**
     * @var bool Whether the field is required
     */
    private bool $required;

    /**
     * Constructor.
     *
     * @param bool $required Whether to make the field required
     * @param int|null $priority Optional priority override
     */
    public function __construct(bool $required = true, ?int $priority = null)
    {
        parent::__construct($priority);
        $this->required = $required;
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfigModifications(): array
    {
        $modifications = [
            'required' => $this->required,
            'attributes' => [
                'required' => $this->required ? 'required' : null,
            ]
        ];

        return $modifications;
    }

    /**
     * {@inheritdoc}
     */
    public function applyToConfig(array $config): array
    {
        $config = parent::applyToConfig($config);

        // If required, add an asterisk to the label if not already present
        if ($this->required && isset($config['label']) && !str_ends_with($config['label'], '*')) {
            $config['label'] .= ' *';
        }

        return $config;
    }
}