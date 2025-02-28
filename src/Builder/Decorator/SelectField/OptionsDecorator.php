<?php

namespace WPSettingsKit\Builder\Decorator\SelectField;

use WPSettingsKit\Attribute\FieldDecorator;
use WPSettingsKit\Builder\Decorator\AbstractFieldBuilderDecorator;

/**
 * Decorator for adding options to select fields.
 */
#[FieldDecorator(
    type: 'select',
    method: 'setOptions',
    priority: 10
)]
class OptionsDecorator extends AbstractFieldBuilderDecorator
{
    /**
     * @var array<string, mixed> Select options (key => label)
     */
    private array $options;

    /**
     * @var bool Whether to replace existing options
     */
    private bool $replace;

    /**
     * Constructor.
     *
     * @param array<string, mixed> $options Select options
     * @param bool $replace Whether to replace existing options
     * @param int|null $priority Optional priority override
     */
    public function __construct(array $options, bool $replace = true, ?int $priority = null)
    {
        parent::__construct($priority);
        $this->options = $options;
        $this->replace = $replace;
    }

    /**
     * {@inheritdoc}
     */
    protected function applyCustomLogic(array $config): array {
        // Merge with existing options if not replacing
        if (!$this->replace && isset($config['options']) && is_array($config['options'])) {
            $config['options'] = array_merge($config['options'], $this->options);
        } else {
            $config['options'] = $this->options;
        }

        return $config;
    }
}