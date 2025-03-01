<?php

namespace WPSettingsKit\Domain\Field\Builder;

use WPSettingsKit\Domain\Field\Base\Interface\IField;
use WPSettingsKit\Domain\Field\Enhancer\BuilderEnhancer\SelectField\OptionsEnhancer;
use WPSettingsKit\Domain\Field\Enhancer\BuilderEnhancer\SelectField\SingleOptionEnhancer;
use WPSettingsKit\Domain\Field\Entity\Basic\SelectField;
use WPSettingsKit\Validation\Rules\SelectField\MaxSelectionsValidator;
use WPSettingsKit\Validation\Rules\SelectField\MinSelectionsValidator;
use WPSettingsKit\Validation\Rules\SelectField\OptionExistsValidator;

/**
 * Builder for select fields with automatic enhancer support.
 *
 * Provides a fluent interface for configuring and building select field objects.
 */
class SelectFieldBuilder extends BaseFieldBuilder
{
    /**
     * Constructor.
     *
     * @param string $key Field unique key
     * @param string $label Field display label
     */
    public function __construct(string $key, string $label)
    {
        parent::__construct($key, $label, 'select');
    }

    /**
     * Set options for the select field.
     *
     * @param array<string, mixed> $options Key-value pairs for options
     * @return self For method chaining
     */
    public function setOptions(array $options): self
    {
        return $this->addEnhancer(new OptionsEnhancer($options));
    }

    /**
     * Add a single option to the select field.
     *
     * @param string $key Option key/value
     * @param string $label Option display label
     * @return self For method chaining
     */
    public function addOption(string $key, string $label): self
    {
        return $this->addEnhancer(new SingleOptionEnhancer($key, $label));
    }

    /**
     * Adds validation that ensures selected options exist in the available options.
     *
     * @param string|null $customMessage Optional custom error message
     * @return self For method chaining
     */
    public function addOptionExistsRule(?string $customMessage = null): self
    {
        $options = $this->getOptionsFromConfig();
        return $this->addValidationRule(
            new OptionExistsValidator($options, $customMessage)
        );
    }

    /**
     * Adds validation that enforces a maximum number of selections.
     *
     * @param int $maxSelections Maximum number of selections allowed
     * @param string|null $customMessage Optional custom error message
     * @return self For method chaining
     */
    public function addMaxSelectionsRule(int $maxSelections, ?string $customMessage = null): self
    {
        return $this->addValidationRule(
            new MaxSelectionsValidator($maxSelections, $customMessage)
        );
    }

    /**
     * Adds validation that enforces a minimum number of selections.
     *
     * @param int $minSelections Minimum number of selections required
     * @param string|null $customMessage Optional custom error message
     * @return self For method chaining
     */
    public function addMinSelectionsRule(int $minSelections, ?string $customMessage = null): self
    {
        return $this->addValidationRule(
            new MinSelectionsValidator($minSelections, $customMessage)
        );
    }

    /**
     * Gets available options from config.
     *
     * @return array<string, string> Option key-value pairs
     */
    protected function getOptionsFromConfig(): array
    {
        // Extract existing options from config
        if (isset($this->config['options']) && is_array($this->config['options'])) {
            return $this->config['options'];
        }

        // Look through enhancers for options
        foreach ($this->enhancers as $enhancer) {
            if ($enhancer instanceof OptionsEnhancer) {
                return $enhancer->getOptions();
            }
        }

        return [];
    }

    /**
     * Builds and returns a SelectField.
     *
     * @return IField The configured select field
     */
    public function build(): IField
    {
        $config = $this->getDecoratedConfig();

        // Add automatic option exists validation if not already added
        if ($this->shouldAddOptionExistsValidation($config)) {
            $this->addOptionExistsRule();
            $config = $this->getDecoratedConfig();
        }

        return new SelectField($config);
    }

    /**
     * Determines if option exists validation should be added automatically.
     *
     * @param array<string, mixed> $config Field configuration
     * @return bool Whether to add option exists validation
     */
    protected function shouldAddOptionExistsValidation(array $config): bool
    {
        // Skip if no options defined
        if (empty($config['options'])) {
            return false;
        }

        // Skip if already has an OptionExistsValidator
        if (isset($config['validation_rules']) && is_array($config['validation_rules'])) {
            foreach ($config['validation_rules'] as $rule) {
                if ($rule instanceof OptionExistsValidator) {
                    return false;
                }
            }
        }

        return true;
    }
}