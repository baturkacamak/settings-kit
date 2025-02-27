<?php

namespace WPSettingsKit\Builder;

use WPSettingsKit\Builder\Decorator\CssClassDecorator;
use WPSettingsKit\Builder\Decorator\DefaultValueDecorator;
use WPSettingsKit\Builder\Decorator\DescriptionDecorator;
use WPSettingsKit\Builder\Decorator\DisabledDecorator;
use WPSettingsKit\Builder\Decorator\RequiredDecorator;
use WPSettingsKit\Builder\Decorator\SelectField\MultipleDecorator;
use WPSettingsKit\Builder\Decorator\SelectField\OptionGroupsDecorator;
use WPSettingsKit\Builder\Decorator\SelectField\OptionsDecorator;
use WPSettingsKit\Builder\Decorator\SelectField\SelectPlaceholderDecorator;
use WPSettingsKit\Builder\Decorator\SelectField\SelectSizeDecorator;
use WPSettingsKit\Builder\Decorator\SelectField\SingleOptionDecorator;
use WPSettingsKit\Field\Base\Interface\IField;
use WPSettingsKit\Field\Basic\SelectField;

/**
 * Builder for select fields using decorator pattern
 */
class SelectFieldBuilder extends BaseFieldBuilder
{
    /**
     * Set options for the select field
     *
     * @param array<string, mixed> $options Key-value pairs for options
     * @return self
     */
    public function setOptions(array $options): self
    {
        return $this->addDecorator(new OptionsDecorator($options));
    }

    /**
     * Add a single option to the select field
     *
     * @param string $key Option key/value
     * @param string $label Option display label
     * @return self
     */
    public function addOption(string $key, string $label): self
    {
        return $this->addDecorator(new SingleOptionDecorator($key, $label));
    }

    /**
     * Enable multiple selections
     *
     * @param bool $multiple Whether to allow multiple selections
     * @return self
     */
    public function setMultiple(bool $multiple = true): self
    {
        return $this->addDecorator(new MultipleDecorator($multiple));
    }

    /**
     * Set number of visible items
     *
     * @param int $size Number of visible items
     * @return self
     */
    public function setSize(int $size): self
    {
        return $this->addDecorator(new SelectSizeDecorator($size));
    }

    /**
     * Add a placeholder option
     *
     * @param string $placeholder Placeholder text
     * @param bool $disabled Whether placeholder option is disabled
     * @return self
     */
    public function setPlaceholder(string $placeholder, bool $disabled = true): self
    {
        return $this->addDecorator(new SelectPlaceholderDecorator($placeholder, $disabled));
    }

    /**
     * Add option groups to select field
     *
     * @param array<string, array<string, string>> $optionGroups Group label => [key => value]
     * @return self
     */
    public function setOptionGroups(array $optionGroups): self
    {
        return $this->addDecorator(new OptionGroupsDecorator($optionGroups));
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
     * @param string|array<string> $value Default field value (string or array for multiple)
     * @return self
     */
    public function setDefaultValue(string|array $value): self
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
     * Build and return a SelectField
     *
     * @return IField The configured select field
     */
    public function build(): IField
    {
        $config = $this->getDecoratedConfig();

        // Ensure options array exists
        if (!isset($config['options'])) {
            $config['options'] = [];
        }

        return new SelectField($config);
    }
}