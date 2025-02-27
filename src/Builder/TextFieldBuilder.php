<?php

namespace WPSettingsKit\Builder;

use WPSettingsKit\Builder\Decorator\CssClassDecorator;
use WPSettingsKit\Builder\Decorator\DefaultValueDecorator;
use WPSettingsKit\Builder\Decorator\DescriptionDecorator;
use WPSettingsKit\Builder\Decorator\DisabledDecorator;
use WPSettingsKit\Builder\Decorator\RequiredDecorator;
use WPSettingsKit\Builder\Decorator\TextField\InputTypeDecorator;
use WPSettingsKit\Builder\Decorator\TextField\MaxLengthDecorator;
use WPSettingsKit\Builder\Decorator\TextField\MinLengthDecorator;
use WPSettingsKit\Builder\Decorator\TextField\PlaceholderDecorator;
use WPSettingsKit\Builder\Decorator\TextField\ReadonlyDecorator;
use WPSettingsKit\Field\Base\Interface\IField;
use WPSettingsKit\Field\Basic\TextField;

/**
 * Builder for text fields using decorator pattern
 */
class TextFieldBuilder extends BaseFieldBuilder
{
    /**
     * Set placeholder text
     *
     * @param string $placeholder Placeholder text
     * @return self
     */
    public function setPlaceholder(string $placeholder): self
    {
        return $this->addDecorator(new PlaceholderDecorator($placeholder));
    }

    /**
     * Set maximum length
     *
     * @param int $maxLength Maximum character length
     * @return self
     */
    public function setMaxLength(int $maxLength): self
    {
        return $this->addDecorator(new MaxLengthDecorator($maxLength));
    }

    /**
     * Set minimum length
     *
     * @param int $minLength Minimum character length
     * @return self
     */
    public function setMinLength(int $minLength): self
    {
        return $this->addDecorator(new MinLengthDecorator($minLength));
    }

    /**
     * Set input type
     *
     * @param string $type HTML input type (text, email, url, etc.)
     * @return self
     */
    public function setInputType(string $type): self
    {
        return $this->addDecorator(new InputTypeDecorator($type));
    }

    /**
     * Set field as readonly
     *
     * @param bool $readonly Whether field is readonly
     * @return self
     */
    public function setReadonly(bool $readonly = true): self
    {
        return $this->addDecorator(new ReadonlyDecorator($readonly));
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
     * @param string $value Default field value
     * @return self
     */
    public function setDefaultValue(string $value): self
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
     * Build and return a TextField
     *
     * @return IField The configured text field
     */
    public function build(): IField
    {
        $config = $this->getDecoratedConfig();
        return new TextField($config);
    }
}