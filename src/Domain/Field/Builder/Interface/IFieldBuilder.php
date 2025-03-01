<?php

namespace WPSettingsKit\Domain\Field\Builder\Interface;

use WPSettingsKit\Domain\Field\Base\Interface\IField;

/**
 * Interface for field builders
 */
interface IFieldBuilder
{
    /**
     * Add a enhancer to the builder
     *
     * @param IFieldBuilderEnhancer $enhancer Enhancer to add
     * @return self
     */
    public function addEnhancer(IFieldBuilderEnhancer $enhancer): self;

    /**
     * Build and return the field
     *
     * @return IField The configured field
     */
    public function build(): IField;
}