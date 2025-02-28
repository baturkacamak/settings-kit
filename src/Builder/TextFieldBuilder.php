<?php

namespace WPSettingsKit\Builder;

use WPSettingsKit\Field\Base\Interface\IField;
use WPSettingsKit\Field\Basic\TextField;
use WPSettingsKit\Validation\Rules\Common\LengthValidationRule;
use WPSettingsKit\Validation\Rules\Common\LengthValidatorEnhanced;
use WPSettingsKit\Validation\Rules\Common\PatternValidationRule;
use WPSettingsKit\Validation\Rules\Common\PatternValidatorEnhanced;
use WPSettingsKit\Validation\Rules\Text\EmailValidationRule;
use WPSettingsKit\Validation\Rules\Text\EmailValidatorEnhanced;
use WPSettingsKit\Validation\Rules\Text\UrlValidationRule;

/**
 * Builder for text fields with automatic decorator support.
 *
 * Provides a fluent interface for configuring and building text field objects.
 */
class TextFieldBuilder extends BaseFieldBuilder
{
    /**
     * Constructor.
     *
     * @param string $key Field unique key
     * @param string $label Field display label
     */
    public function __construct(string $key, string $label)
    {
        parent::__construct($key, $label, 'text');
    }

    /**
     * Sets the input type (text, email, url, etc.).
     *
     * @param string $type HTML input type
     * @return self For method chaining
     */
    public function setInputType(string $type): self
    {
        $validTypes                 = ['text', 'email', 'url', 'tel', 'password', 'number', 'search'];
        $this->config['input_type'] = in_array($type, $validTypes) ? $type : 'text';

        // Automatically add appropriate validation based on type
        if ($type === 'email') {
            $this->addEmailRule();
        } elseif ($type === 'url') {
            $this->addUrlRule();
        } elseif ($type === 'tel') {
            $this->addPatternRule('/^[0-9+\-\s()]*$/', 'Valid phone number format');
        }

        return $this;
    }

    /**
     * Adds an email validation rule.
     *
     * @param bool $checkDns Whether to verify domain has valid MX records
     * @param string|null $customMessage Optional custom error message
     * @return self For method chaining
     */
    public function addEmailRule(bool $checkDns = false, ?string $customMessage = null): self
    {
        return $this->addValidationRule(
            new EmailValidationRule($checkDns, $customMessage)
        );
    }

    /**
     * Adds a URL validation rule.
     *
     * @param array<string>|null $allowedSchemes Allowed URL schemes
     * @param string|null $customMessage Optional custom error message
     * @return self For method chaining
     */
    public function addUrlRule(?array $allowedSchemes = null, ?string $customMessage = null): self
    {
        $schemes = $allowedSchemes ?? ['http', 'https'];
        return $this->addValidationRule(
            new UrlValidationRule($schemes, false, $customMessage)
        );
    }

    /**
     * Adds a pattern validation rule.
     *
     * @param string $pattern Regular expression pattern
     * @param string $description Human-readable description of the pattern
     * @param string|null $customMessage Optional custom error message
     * @return self For method chaining
     */
    public function addPatternRule(string $pattern, string $description = '', ?string $customMessage = null): self
    {
        return $this->addValidationRule(
            new PatternValidationRule($pattern, $description, $customMessage)
        );
    }

    /**
     * Adds a length validation rule.
     *
     * @param int|null $minLength Minimum allowed length
     * @param int|null $maxLength Maximum allowed length
     * @param string|null $customMessage Optional custom error message
     * @return self For method chaining
     */
    public function addLengthRule(?int $minLength = null, ?int $maxLength = null, ?string $customMessage = null): self
    {
        return $this->addValidationRule(
            new LengthValidationRule($minLength, $maxLength, $customMessage)
        );
    }

    /**
     * Builds and returns a TextField.
     *
     * @return IField The configured text field
     */
    public function build(): IField
    {
        $config = $this->getDecoratedConfig();
        return new TextField($config);
    }
}