<?php

namespace  WPSettingsKit\Builder\Interface;

use  WPSettingsKit\Decorator\Interface\IFieldDecorator;
use  WPSettingsKit\Dependency\Interface\IFieldDependency;
use  WPSettingsKit\Field\Interface\IField;
use  WPSettingsKit\Validation\Interface\IValidationRule;
use  WPSettingsKit\Validation\Interface\IValueTransformer;

/**
 * Interface for field builder
 */
interface IFieldBuilder
{
    /**
     * Set the field key
     *
     * @param string $key
     * @return self
     */
    public function setKey(string $key): self;

    /**
     * Set the field label
     *
     * @param string $label
     * @return self
     */
    public function setLabel(string $label): self;

    /**
     * Set if the field is required
     *
     * @param bool $required
     * @return self
     */
    public function setRequired(bool $required): self;

    /**
     * Set the field description
     *
     * @param string $description
     * @return self
     */
    public function setDescription(string $description): self;

    /**
     * Add a validation rule
     *
     * @param IValidationRule $rule
     * @return self
     */
    public function addValidationRule(IValidationRule $rule): self;

    /**
     * Set the default value
     *
     * @param mixed $value
     * @return self
     */
    public function setDefaultValue(mixed $value): self;

    /**
     * Add a dependency
     *
     * @param IFieldDependency $dependency
     * @return self
     */
    public function addDependency(IFieldDependency $dependency): self;

    /**
     * Set value transformer
     *
     * @param IValueTransformer $transformer
     * @return self
     */
    public function setTransformer(IValueTransformer $transformer): self;

    /**
     * Set field decorator
     *
     * @param IFieldDecorator $decorator
     * @return self
     */
    public function setDecorator(IFieldDecorator $decorator): self;

    /**
     * Build and return the field
     *
     * @return IField
     */
    public function build(): IField;
}