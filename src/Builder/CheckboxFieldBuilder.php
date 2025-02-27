<?php

namespace WPSettingsKit\Builder;

use WPSettingsKit\Builder\Decorator\CheckboxField\CheckedValueDecorator;
use WPSettingsKit\Builder\Decorator\CheckboxField\LabelPositionDecorator;
use WPSettingsKit\Builder\Decorator\CheckboxField\SwitchStyleDecorator;
use WPSettingsKit\Builder\Decorator\CheckboxField\UncheckedValueDecorator;
use WPSettingsKit\Builder\Decorator\CssClassDecorator;
use WPSettingsKit\Builder\Decorator\DefaultValueDecorator;
use WPSettingsKit\Builder\Decorator\DescriptionDecorator;
use WPSettingsKit\Builder\Decorator\DisabledDecorator;
use WPSettingsKit\Builder\Decorator\RequiredDecorator;
use WPSettingsKit\Field\Base\Interface\IField;
use WPSettingsKit\Field\Basic\CheckboxField;

/**
 * Builder for checkbox fields using decorator pattern
 */
class CheckboxFieldBuilder extends BaseFieldBuilder
{
    /**
     * Set checked value
     *
     * @param mixed $value Value when checkbox is checked
     * @return self
     */
    public function setCheckedValue(mixed $value): self
    {
        return $this->addDecorator(new CheckedValueDecorator($value));
    }

    /**
     * Set unchecked value
     *
     * @param mixed $value Value when checkbox is unchecked
     * @return self
     */
    public function setUncheckedValue(mixed $value): self
    {
        return $this->addDecorator(new UncheckedValueDecorator($value));
    }

    /**
     * Set checkbox as switch style
     *
     * @param bool $switchStyle Whether to style as switch
     * @return self
     */
    public function setSwitchStyle(bool $switchStyle = true): self
    {
        return $this->addDecorator(new SwitchStyleDecorator($switchStyle));
    }

    /**
     * Set label position
     *
     * @param string $position Position ('before' or 'after')
     * @return self
     */
    public function setLabelPosition(string $position): self
    {
        return $this->addDecorator(new LabelPositionDecorator($position));
    }

    /**
     * Set field as disabled
     *
     * @param bool $disabled Whether field is disabled
     * @return self
     */
    public function setDisabled(bool $disabled = true): self
    {
        return $this->addDecorator(new DisabledDecorator($disabled));
    }

    /**
     * Set field as required
     *
     * @param bool $required Whether field is required
     * @return self
     */
    public function setRequired(bool $required = true): self
    {
        return $this->addDecorator(new RequiredDecorator($required));
    }

    /**
     * Set field description
     *
     * @param string $description Field description text
     * @return self
     */
    public function setDescription(string $description): self
    {
        return $this->addDecorator(new DescriptionDecorator($description));
    }

    /**
     * Set default value
     *
     * @param mixed $value Default field value
     * @return self
     */
    public function setDefaultValue(mixed $value): self
    {
        return $this->addDecorator(new DefaultValueDecorator($value));
    }

    /**
     * Set CSS classes
     *
     * @param string $cssClass CSS classes to add
     * @return self
     */
    public function setCssClass(string $cssClass): self
    {
        return $this->addDecorator(new CssClassDecorator($cssClass));
    }

    /**
     * Build and return a CheckboxField
     *
     * @return IField The configured checkbox field
     */
    public function build(): IField
    {
        $config = $this->getDecoratedConfig();
        return new CheckboxField($config);
    }
}