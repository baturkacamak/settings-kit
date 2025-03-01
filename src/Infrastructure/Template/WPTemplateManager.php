<?php

namespace WPSettingsKit\Infrastructure\Template;

use WPSettingsKit\Infrastructure\Template\Interface\ITemplateManager;

/**
 * WordPress-specific template manager implementation
 */
class WPTemplateManager implements ITemplateManager {
    private string $optionName;
    private array $templates = [];

    public function __construct(string $optionName = 'settings_templates') {
        $this->optionName = $optionName;
        $this->loadTemplates();
    }

    /**
     * @inheritDoc
     */
    public function saveAsTemplate(string $name): bool {
        global $wpdb;

        // Get all settings
        $settings = [];
        $sql = "SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE 'settings_%'";
        $results = $wpdb->get_results($sql);

        foreach ($results as $row) {
            $settings[$row->option_name] = maybe_unserialize($row->option_value);
        }

        $this->templates[$name] = [
            'name' => $name,
            'settings' => $settings,
            'created_at' => current_time('mysql'),
            'created_by' => get_current_user_id(),
        ];

        return $this->saveTemplates();
    }

    /**
     * @inheritDoc
     */
    public function loadTemplate(string $name): bool {
        if (!isset($this->templates[$name])) {
            return false;
        }

        $template = $this->templates[$name];

        foreach ($template['settings'] as $key => $value) {
            update_option($key, $value);
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getTemplates(): array {
        return $this->templates;
    }

    /**
     * Load templates from database
     */
    private function loadTemplates(): void {
        $templates = get_option($this->optionName, []);
        $this->templates = is_array($templates) ? $templates : [];
    }

    /**
     * Save templates to database
     */
    private function saveTemplates(): bool {
        return update_option($this->optionName, $this->templates);
    }
}
