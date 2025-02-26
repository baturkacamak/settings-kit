<?php

namespace WPSettingsKit\Builder;

use WPSettingsKit\Builder\Interface\IFieldBuilder;
use WPSettingsKit\Decorator\Interface\IFieldDecorator;
use WPSettingsKit\Dependency\Interface\IFieldDependency;
use WPSettingsKit\Exception\BuilderException;
use WPSettingsKit\Field\Base\Interface\IField;
use WPSettingsKit\Validation\Interface\IValidationRule;
use WPSettingsKit\Validation\Interface\IValueTransformer;

/**
 * Abstract base class for field builders
 */
abstract class AbstractFieldBuilder implements IFieldBuilder {
    /**
     * Configuration array
     */
    protected array $config = [];

    /**
     * Validation rules
     */
    protected array $validationRules = [];

    /**
     * Field dependencies
     */
    protected array $dependencies = [];

    /**
     * @inheritDoc
     * @throws BuilderException
     */
    public function setKey(string $key): self
    {
        if (empty($key)) {
            throw new BuilderException("Field key cannot be empty");
        }
        $this->config['key'] = $key;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setLabel(string $label): self {
        $this->config['label'] = $label;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setRequired(bool $required): self {
        $this->config['required'] = $required;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setDescription(string $description): self {
        $this->config['description'] = $description;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addValidationRule(IValidationRule $rule): self {
        $this->validationRules[] = $rule;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setDefaultValue(mixed $value): self {
        $this->config['default_value'] = $value;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addDependency(IFieldDependency $dependency): self {
        $this->dependencies[] = $dependency;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setTransformer(IValueTransformer $transformer): self {
        $this->config['transformer'] = $transformer;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setDecorator(IFieldDecorator $decorator): self {
        $this->config['decorator'] = $decorator;
        return $this;
    }

    /**
     * @inheritDoc
     */
    abstract public function build(): IField;

    /**
     * Get the complete configuration
     * @throws BuilderException
     */
    protected function getConfig(): array
    {
        if (!isset($this->config['key'])) {
            throw new BuilderException("Cannot build field: key is not set");
        }
        return array_merge($this->config, [
            'validation_rules' => $this->validationRules,
            'dependencies' => $this->dependencies,
        ]);
    }
}