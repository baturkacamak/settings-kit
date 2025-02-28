<?php

namespace WPSettingsKit\Builder\Enhancer\TextField;

use WPSettingsKit\Attribute\FieldEnhancer;
use WPSettingsKit\Builder\Enhancer\AbstractFieldBuilderEnhancer;

/**
 * Enhancer for setting pattern validation on text fields.
 */
#[FieldEnhancer(
    type: 'text',
    method: 'setPattern',
    priority: 25
)]
class PatternEnhancer extends AbstractFieldBuilderEnhancer
{
    /**
     * @var string HTML input pattern (regular expression)
     */
    private string $pattern;

    /**
     * @var string|null Pattern description for error messages
     */
    private ?string $description;

    /**
     * Constructor.
     *
     * @param string $pattern HTML input pattern (regular expression)
     * @param string|null $description Pattern description for error messages
     * @param int|null $priority Optional priority override
     */
    public function __construct(string $pattern, ?string $description = null, ?int $priority = null)
    {
        parent::__construct($priority);
        $this->pattern = $pattern;
        $this->description = $description;
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfigModifications(): array
    {
        $modifications = [
            'pattern' => $this->pattern,
            'attributes' => [
                'pattern' => $this->pattern
            ]
        ];

        if ($this->description !== null) {
            $modifications['pattern_description'] = $this->description;
            $modifications['attributes']['title'] = $this->description;
        }

        return $modifications;
    }
}