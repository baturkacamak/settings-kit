<?php

namespace  WPSettingsKit\Validation\Interface;

interface IValueTransformer
{
    /**
     * Transform a value
     *
     * @param mixed $value Value to transform
     * @return mixed Transformed value
     */
    public function transform(mixed $value): mixed;

    /**
     * Reverse transform a value
     *
     * @param mixed $value Value to reverse transform
     * @return mixed Reverse transformed value
     */
    public function reverseTransform(mixed $value): mixed;
}
