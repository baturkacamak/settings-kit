<?php

namespace WPSettingsKit\Builder\Decorator\CheckboxField;

use WPSettingsKit\Attribute\FieldDecorator;
use WPSettingsKit\Builder\Decorator\AbstractFieldBuilderDecorator;

/**
 * Decorator for setting an inline label for checkbox fields.
 */
#[FieldDecorator(
    type: 'checkbox',
    method: 'setInlineLabel',
    priority: 30
)]
class InlineLabelDecorator extends AbstractFieldBuilderDecorator
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