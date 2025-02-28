<?php

namespace WPSettingsKit\Builder\Enhancer\TextField;

use WPSettingsKit\Attribute\FieldEnhancer;
use WPSettingsKit\Builder\Enhancer\AbstractFieldBuilderEnhancer;

/**
 * Enhancer for adding prefix and/or suffix text to input fields.
 */
#[FieldEnhancer(
    type: ['text', 'number'],
    method: 'setPrefixSuffix',
    priority: 40
)]
class PrefixSuffixEnhancer extends AbstractFieldBuilderEnhancer
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
        parent::__construct($priority, ['text', 'number']);
        $this->prefix = $prefix;
        $this->suffix = $suffix;
    }

    /**
     * Get configuration values.
     *
     * @return array<string, mixed> Configuration values
     */
    protected function getConfigValues(): array
    {
        $values = [];

        if ($this->prefix !== null) {
            $values['prefix'] = $this->prefix;
        }

        if ($this->suffix !== null) {
            $values['suffix'] = $this->suffix;
        }

        return $values;
    }
}