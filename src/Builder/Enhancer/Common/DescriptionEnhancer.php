<?php

namespace WPSettingsKit\Builder\Enhancer\Common;

use WPSettingsKit\Attribute\FieldEnhancer;
use WPSettingsKit\Builder\Enhancer\AbstractFieldBuilderEnhancer;

/**
 * Enhancer for adding descriptions to fields.
 */
#[FieldEnhancer(
    type: 'all',
    method: 'setDescription',
    priority: 90
)]
class DescriptionEnhancer extends AbstractFieldBuilderEnhancer
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