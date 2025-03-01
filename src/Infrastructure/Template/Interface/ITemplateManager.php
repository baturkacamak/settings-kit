<?php

namespace   WPSettingsKit\Infrastructure\Template\Interface;

interface ITemplateManager {
    /**
     * Save current settings as a template
     *
     * @param string $name Template name
     * @return bool True on success, false on failure
     */
    public function saveAsTemplate(string $name): bool;

    /**
     * Load settings from a template
     *
     * @param string $name Template name
     * @return bool True on success, false on failure
     */
    public function loadTemplate(string $name): bool;

    /**
     * Get all available templates
     *
     * @return array List of templates
     */
    public function getTemplates(): array;
}
