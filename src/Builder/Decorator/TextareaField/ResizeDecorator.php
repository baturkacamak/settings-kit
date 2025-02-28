<?php

namespace WPSettingsKit\Builder\Decorator\TextareaField;

use WPSettingsKit\Attribute\FieldDecorator;
use WPSettingsKit\Builder\Decorator\AbstractFieldBuilderDecorator;

/**
 * Decorator for controlling textarea resize behavior.
 */
#[FieldDecorator(
    type: 'textarea',
    method: 'setResize',
    priority: 20
)]
class ResizeDecorator extends AbstractFieldBuilderDecorator
{
    /**
     * @var string Resize behavior ('none', 'both', 'horizontal', 'vertical')
     */
    private string $resize;

    /**
     * @var array<string> Valid resize values
     */
    private array $validResizeValues = ['none', 'both', 'horizontal', 'vertical'];

    /**
     * Constructor.
     *
     * @param string $resize Resize behavior ('none', 'both', 'horizontal', 'vertical')
     * @param int|null $priority Optional priority override
     */
    public function __construct(string $resize, ?int $priority = null)
    {
        parent::__construct($priority);
        $this->resize = in_array($resize, $this->validResizeValues) ? $resize : 'both';
    }

    /**
     * {@inheritdoc}
     */
    public function applyToConfig(array $config): array
    {
        $config = parent::applyToConfig($config);

        // Add inline style for resize control
        if (!isset($config['attributes'])) {
            $config['attributes'] = [];
        }

        if (!isset($config['attributes']['style'])) {
            $config['attributes']['style'] = '';
        }

        // Add resize style
        $config['attributes']['style'] .= 'resize: ' . $this->resize . ';';

        return $config;
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfigModifications(): array
    {
        return [
            'resize'    => $this->resize,
            'css_style' => 'resize: ' . $this->resize . ';',
        ];
    }
}