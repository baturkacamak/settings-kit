<?php

namespace WPSettingsKit\Domain\Field\Enhancer\BuilderEnhancer\CheckboxField;

use WPSettingsKit\Domain\Field\Enhancer\Attribute\FieldEnhancer;
use WPSettingsKit\Domain\Field\Enhancer\BuilderEnhancer\AbstractFieldBuilderEnhancer;

/**
 * Enhancer for styling checkbox as a toggle switch.
 */
#[FieldEnhancer(
    type: 'checkbox',
    method: 'setSwitchStyle',
    priority: 25
)]
class SwitchStyleEnhancer extends AbstractFieldBuilderEnhancer
{
    /**
     * @var bool Whether to style as a switch
     */
    private bool $switchStyle;

    /**
     * @var string|null On text label
     */
    private ?string $onText;

    /**
     * @var string|null Off text label
     */
    private ?string $offText;

    /**
     * Constructor.
     *
     * @param bool $switchStyle Whether to style as a switch
     * @param string|null $onText Text to display when switch is on
     * @param string|null $offText Text to display when switch is off
     * @param int|null $priority Optional priority override
     */
    public function __construct(
        bool $switchStyle = true,
        ?string $onText = null,
        ?string $offText = null,
        ?int $priority = null
    ) {
        parent::__construct($priority);
        $this->switchStyle = $switchStyle;
        $this->onText = $onText;
        $this->offText = $offText;
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfigModifications(): array
    {
        $modifications = [
            'switch_style' => $this->switchStyle,
        ];

        if ($this->onText !== null) {
            $modifications['on_text'] = $this->onText;
        }

        if ($this->offText !== null) {
            $modifications['off_text'] = $this->offText;
        }

        // Add necessary attributes and classes for switch styling
        if ($this->switchStyle) {
            if (!isset($modifications['attributes'])) {
                $modifications['attributes'] = [];
            }

            $modifications['attributes']['data-toggle'] = 'switch';

            // Add classes for switch styling
            $class = 'wsk-switch';
            $modifications['css_class'] = isset($modifications['css_class'])
                ? $modifications['css_class'] . ' ' . $class
                : $class;
        }

        return $modifications;
    }
}