<?php

namespace WPSettingsKit\Builder\Interface;

use WPSettingsKit\Field\Base\Interface\IField;

/**
 * Interface for field builders
 */
interface IFieldBuilder
{
    /**
     * Add a decorator to the builder
     *
     * @param IFieldBuilderDecorator $decorator Decorator to add
     * @return self
     */
    public function addDecorator(IFieldBuilderDecorator $decorator): self;

    /**
     * Build and return the field
     *
     * @return IField The configured field
     */
    public function build(): IField;
}