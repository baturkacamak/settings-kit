<?php

namespace WPSettingsKit\Builder\Decorator\TextField;

use WPSettingsKit\Attribute\FieldDecorator;
use WPSettingsKit\Builder\Decorator\AbstractFieldBuilderDecorator;

/**
 * Decorator for adding prefix and/or suffix text to input fields.
 */
#[FieldDecorator(
    type: ['text', 'number'],
    method: 'setPrefixSuffix',
    priority: 40
)]
class PrefixSuffixDecorator extends AbstractFieldBuilderDecorator
{
    /**
     * @var string|null Text to display before the input
     */
    private ?string $prefix;

    /**
     * @var string|null Text to display after the input
     */
    private ?string $suffix;

    /**
     * Constructor.
     *
     * @param string|null $prefix Text to display before the input
     * @param string|null $suffix Text to display after the input
     * @param int|null $priority Optional priority override
     */
    public function __construct(?string $prefix = null, ?string $suffix = null, ?int $priority = null)
    {
        parent::__construct($priority);
        $this->prefix = $prefix;
        $this->suffix = $suffix;
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfigModifications(): array
    {
        $modifications = [];

        if ($this->prefix !== null) {
            $modifications['prefix'] = $this->prefix;
        }

        if ($this->suffix !== null) {
            $modifications['suffix'] = $this->suffix;
        }

        return $modifications;
    }
}