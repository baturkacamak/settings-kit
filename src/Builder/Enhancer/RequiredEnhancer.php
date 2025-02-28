<?php

namespace WPSettingsKit\Builder\Enhancer\Common;

use WPSettingsKit\Attribute\FieldEnhancer;
use WPSettingsKit\Builder\Enhancer\AbstractFieldBuilderEnhancer;

/**
 * Enhancer for setting required state on fields.
 *
 * This enhancer can be applied to all field types.
 */
#[FieldEnhancer(
    type: 'all',
    method: 'setRequired',
    priority: 5
)]
class RequiredEnhancer extends AbstractFieldBuilderEnhancer
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