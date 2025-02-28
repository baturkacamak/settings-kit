<?php

namespace WPSettingsKit\Builder\Interface;

use WPSettingsKit\Field\Base\Interface\IField;

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