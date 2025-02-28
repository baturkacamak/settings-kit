<?php

namespace WPSettingsKit\Builder\Enhancer\CheckboxField;

use WPSettingsKit\Attribute\FieldEnhancer;
use WPSettingsKit\Builder\Enhancer\AbstractFieldBuilderEnhancer;

/**
 * Enhancer for setting an inline label for checkbox fields.
 */
#[FieldEnhancer(
    type: 'checkbox',
    method: 'setInlineLabel',
    priority: 30
)]
class InlineLabelEnhancer extends AbstractFieldBuilderEnhancer
{
    /**
     * @var string Inline label text
     */
    private string $inlineLabel;

    /**
     * Constructor.
     *
     * @param string $inlineLabel Inline label text
     * @param int|null $priority Optional priority override
     */
    public function __construct(string $inlineLabel, ?int $priority = null)
    {
        parent::__construct($priority);
        $this->inlineLabel = $inlineLabel;
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfigModifications(): array
    {
        return [
            'inline_label' => $this->inlineLabel,
        ];
    }
}