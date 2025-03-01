<?php

namespace WPSettingsKit\Field\Base;

use WPSettingsKit\Dependency\Interface\IFieldDependency;
use WPSettingsKit\Enhancer\Interface\IFieldEnhancer;
use WPSettingsKit\Event\EventManager;
use WPSettingsKit\Event\Interface\IFieldEventDispatcher;
use WPSettingsKit\Exception\ValidationException;
use WPSettingsKit\Field\Base\Interface\IField;
use WPSettingsKit\Field\Base\Interface\IFieldRenderer;
use WPSettingsKit\Validation\Base\Interface\IValidationRule;
use WPSettingsKit\Validation\Base\Interface\IValueTransformer;
use WPSettingsKit\Validation\ValidationChain;

/**
 * Abstract base class for all fields
 *
 * Provides common functionality for field implementations, including validation,
 * event dispatching, and HTML attribute generation.
 */
abstract class AbstractField implements IField
{
    /**
     * @var ValidationChain The chain of validation rules for the field
     */
    protected ValidationChain $validationChain;

    /**
     * Constructor for AbstractField.
     *
     * @param string $key The unique identifier for the field.
     * @param string $label The display label for the field.
     * @param mixed $value The initial value of the field.
     * @param bool $required Whether the field is required.
     * @param string $description The description of the field.
     * @param array<IValidationRule> $validationRules List of validation rules to apply.
     * @param array<IFieldDependency> $dependencies List of field dependencies.
     * @param IValueTransformer|null $transformer Optional transformer for field values.
     * @param IFieldEnhancer|null $enhancer Optional enhancer for field output.
     * @param IFieldEventDispatcher|null $eventDispatcher Event dispatcher, defaults to a new EventManager if null.
     * @param IFieldRenderer|null $renderer The renderer for HTML output, defaults to a field-specific renderer if null.
     */
    public function __construct(
        protected readonly string $key,
        protected string          $label,
        protected mixed           $value = null,
        protected bool            $required = false,
        protected string          $description = '',
        protected array           $validationRules = [],
        protected array           $dependencies = [],
        ?IValueTransformer        $transformer = null,
        ?IFieldEnhancer           $enhancer = null,
        ?IFieldEventDispatcher    $eventDispatcher = null,
        ?IFieldRenderer           $renderer = null
    )
    {
        $this->transformer     = $transformer;
        $this->enhancer        = $enhancer;
        $this->eventDispatcher = $eventDispatcher ?? new EventManager();
        $this->renderer        = $renderer ?? $this->getDefaultRenderer();
        $this->validationChain = new ValidationChain();

        foreach ($this->validationRules as $rule) {
            $this->validationChain->addValidator($rule);
        }
        if ($value !== null) {
            $this->setValue($value);
        }

        do_action('wp_settings_field_constructed', $this);
    }

    /**
     * Provides the default renderer for the field.
     *
     * @return IFieldRenderer The default renderer instance specific to the field type.
     */
    abstract protected function getDefaultRenderer(): IFieldRenderer;

    /**
     * Sets the field value and triggers related events
     *
     * @param mixed $value The new value to set
     */
    public function setValue(mixed $value): void
    {
        $this->eventDispatcher->dispatch('before_set_value', ['field' => $this, 'value' => $value]);
        $this->value = $value;
        $this->eventDispatcher->dispatch('after_set_value', $this);
    }

    /**
     * Validates the field value and collects error messages if validation fails.
     *
     * @return bool True if validation passes, false otherwise.
     * @throws ValidationException If validation fails and errors are present.
     */
    public function validate(): bool
    {
        $this->eventDispatcher->dispatch('before_validate', $this);
        do_action('wp_settings_field_before_validate', $this);

        $errors = $this->validationChain->getErrors($this->value);
        $errors = apply_filters('wp_settings_field_validation_errors', $errors, $this);

        if (!empty($errors)) {
            $exception = new ValidationException(implode('; ', $errors));
            do_action('wp_settings_field_validation_failed', $this, $errors, $exception);
            throw $exception;
        }

        $this->eventDispatcher->dispatch('after_validate', ['field' => $this, 'is_valid' => true]);
        do_action('wp_settings_field_after_validate', $this);
        return true;
    }

    /**
     * Gets validation errors for the current field value.
     *
     * @return array<string> An array of error messages if validation fails, empty array otherwise.
     */
    public function getValidationErrors(): array
    {
        $errors = $this->validationChain->getErrors($this->value);
        return apply_filters('wp_settings_field_validation_errors', $errors, $this);
    }

    /**
     * Renders the field as HTML with dependency attributes if applicable.
     *
     * @return string The rendered HTML markup.
     */
    abstract public function render(): string;

    /**
     * Sanitizes the field value
     *
     * @return mixed The sanitized value
     */
    abstract public function sanitize(): mixed;

    /**
     * Gets the current field value, transformed if applicable
     *
     * @return mixed The field value
     */
    public function getValue(): mixed
    {
        if ($this->transformer) {
            return $this->transformer->transform($this->value);
        }
        return $this->value;
    }

    /**
     * Gets the unique key of the field
     *
     * @return string The field key
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Gets the display label of the field
     *
     * @return string The field label
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Checks if the field is required
     *
     * @return bool True if required, false otherwise
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * Gets the field description
     *
     * @return string The field description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Builds the HTML attribute string including dependency data attributes.
     *
     * @param array<string, mixed> $attributes Base attributes for the field.
     * @return string The complete attribute string.
     */
    public function buildAttributeString(array $attributes): string
    {
        $pairs = [];
        foreach ($attributes as $key => $value) {
            if ($value === true) {
                $pairs[] = $key;
            } elseif ($value !== false && $value !== null) {
                $pairs[] = sprintf('%s="%s"', $key, esc_attr($value));
            }
        }

        foreach ($this->dependencies as $dependency) {
            $pairs[] = $dependency->getDataAttributes();
        }

        return apply_filters('wp_settings_field_attribute_string', implode(' ', $pairs), $attributes, $this);
    }
}