<?php

namespace   WPSettingsKit\Enhancer\Interface;

use WPSettingsKit\Field\Base\Interface\IField;

interface IFieldEnhancer {
    /**
     * Decorate field output
     *
     * @param string $html Original field HTML
     * @param IField $field Field instance
     * @return string Decorated HTML
     */
    public function decorate(string $html, IField $field): string;

    /**
     * Get enhancer priority
     * Lower numbers run first
     *
     * @return int Priority number
     */
    public function getPriority(): int;
}
