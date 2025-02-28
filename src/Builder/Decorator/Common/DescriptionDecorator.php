<?php

namespace WPSettingsKit\Builder\Decorator\Common;

use WPSettingsKit\Attribute\FieldDecorator;
use WPSettingsKit\Builder\Decorator\AbstractFieldBuilderDecorator;

/**
 * Decorator for adding descriptions to fields.
 */
#[FieldDecorator(
    type: 'all',
    method: 'setDescription',
    priority: 90
)]
class DescriptionDecorator extends AbstractFieldBuilderDecorator
{
    /**
     * @var string Description text
     */
    private string $description;

    /**
     * Constructor.
     *
     * @param string $description Description text
     * @param int|null $priority Optional priority override
     */
    public function __construct(string $description, ?int $priority = null)
    {
        parent::__construct($priority);
        $this->description = $description;
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfigModifications(): array
    {
        return [
            'description' => $this->description
        ];
    }
}