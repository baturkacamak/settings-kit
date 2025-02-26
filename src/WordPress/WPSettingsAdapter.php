<?php

namespace WPSettingsKit\WordPress;

use  WPSettingsKit\Field\Interface\IField;

/**
 * WordPress settings page adapter
 */
class WPSettingsAdapter {
    private string $slug;
    private array $fields = [];

    public function __construct(string $slug) {
        $this->slug = $slug;
    }

    /**
     * Register settings with WordPress
     */
    public function registerSettings(): void {
        add_action('admin_init', [$this, 'initializeSettings']);
    }

    /**
     * Initialize settings during admin_init
     */
    public function initializeSettings(): void {
        register_setting(
            $this->slug,
            $this->slug,
            [
                'sanitize_callback' => [$this, 'sanitizeSettings'],
            ]
        );

        foreach ($this->fields as $field) {
            $this->registerField($field);
        }
    }

    /**
     * Add a settings section
     */
    public function addSettingsSection(string $sectionId, string $title): void {
        \add_settings_section(
            $sectionId,
            $title,
            null,
            $this->slug
        );
    }

    /**
     * Add a settings field
     */
    public function addSettingsField(IField $field, string $section = 'default'): void {
        $this->fields[] = $field;

        add_settings_field(
            $field->getKey(),
            $field->getLabel(),
            [$this, 'renderField'],
            $this->slug,
            $section,
            ['field' => $field]
        );
    }

    /**
     * Render a field
     */
    public function renderField(array $args): void {
        /** @var IField $field */
        $field = $args['field'];
        echo $field->render();

        if ($description = $field->getDescription()) {
            printf(
                '<p class="description">%s</p>',
                esc_html($description)
            );
        }
    }

    /**
     * Sanitize settings before save
     */
    public function sanitizeSettings(array $input): array {
        $output = [];

        foreach ($this->fields as $field) {
            $key = $field->getKey();
            if (isset($input[$key])) {
                $field->setValue($input[$key]);
                $output[$key] = $field->sanitize();
            }
        }

        return $output;
    }
}
