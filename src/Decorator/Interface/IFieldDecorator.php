<?php

namespace   WPSettingsKit\Decorator\Interface;

use  WPSettingsKit\Field\Interface\IField;

interface IFieldDecorator {
    /**
     * Decorate field output
     *
     * @param string $html Original field HTML
     * @param IField $field Field instance
     * @return string Decorated HTML
     */
    public function decorate(string $html, IField $field): string;

    /**
     * Get decorator priority
     * Lower numbers run first
     *
     * @return int Priority number
     */
    public function getPriority(): int;
}
