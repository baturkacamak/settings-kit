<?php

namespace WPSettingsKit\Builder;

use WPSettingsKit\Builder\Decorator\CheckboxField\CheckedValueDecorator;
use WPSettingsKit\Builder\Decorator\CheckboxField\UncheckedValueDecorator;
use WPSettingsKit\Field\Base\Interface\IField;
use WPSettingsKit\Field\Basic\CheckboxField;

/**
 * Builder for checkbox fields with automatic decorator support.
 *
 * Provides a fluent interface for configuring and building checkbox field objects.
 */
class CheckboxFieldBuilder extends BaseFieldBuilder
{
    /**
     * Constructor.
     *
     * @param string $key Field unique key
     * @param string $label Field display label
     */
    public function __construct(string $key, string $label)
    {
        parent::__construct($key, $label, 'checkbox');
    }

    /**
     * Creates a boolean checkbox (true/false).
     *
     * @return self For method chaining
     */
    public function asBoolean(): self
    {
        $this->setCheckedValue(true);
        $this->setUncheckedValue(false);
        return $this;
    }

    /**
     * Set checked value.
     *
     * @param mixed $value Value when checkbox is checked
     * @return self For method chaining
     */
    public function setCheckedValue(mixed $value): self
    {
        return $this->addDecorator(new CheckedValueDecorator($value));
    }

    /**
     * Set unchecked value.
     *
     * @param mixed $value Value when checkbox is unchecked
     * @return self For method chaining
     */
    public function setUncheckedValue(mixed $value): self
    {
        return $this->addDecorator(new UncheckedValueDecorator($value));
    }

    /**
     * Creates a yes/no checkbox.
     *
     * @return self For method chaining
     */
    public function asYesNo(): self
    {
        $this->setCheckedValue('yes');
        $this->setUncheckedValue('no');
        return $this;
    }

    /**
     * Creates an enabled/disabled checkbox.
     *
     * @return self For method chaining
     */
    public function asEnabledDisabled(): self
    {
        $this->setCheckedValue('enabled');
        $this->setUncheckedValue('disabled');
        return $this;
    }

    /**
     * Creates a numeric 1/0 checkbox.
     *
     * @return self For method chaining
     */
    public function asNumeric(): self
    {
        $this->setCheckedValue(1);
        $this->setUncheckedValue(0);
        return $this;
    }

    /**
     * Builds and returns a CheckboxField.
     *
     * @return IField The configured checkbox field
     */
    public function build(): IField
    {
        $config = $this->getDecoratedConfig();

        // Set default values if not already set
        if (!isset($config['checked_value'])) {
            $this->setCheckedValue(true);
        }

        if (!isset($config['unchecked_value'])) {
            $this->setUncheckedValue(false);
        }

        $config = $this->getDecoratedConfig();
        return new CheckboxField($config);
    }
}