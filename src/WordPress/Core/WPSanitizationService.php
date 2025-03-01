<?php

namespace WPSettingsKit\WordPress\Core;

use WPSettingsKit\WordPress\Core\Interface\ISanitizationService;

class WPSanitizationService implements ISanitizationService {
    /**
     * @inheritDoc
     */
    public function sanitizeTextField(string|array $value): string|array {
        return sanitize_text_field($value);
    }

    /**
     * @inheritDoc
     */
    public function sanitizeTextarea(string $value): string {
        return sanitize_textarea_field($value);
    }

    /**
     * @inheritDoc
     */
    public function escAttr(string $value): string {
        return esc_attr($value);
    }

    /**
     * @inheritDoc
     */
    public function escHtml(string $value): string {
        return esc_html($value);
    }

    /**
     * @inheritDoc
     */
    public function escUrl(string $value): string {
        return esc_url($value);
    }

    /**
     * @inheritDoc
     */
    public function escUrlRaw(string $value): string {
        return esc_url_raw($value);
    }

    /**
     * @inheritDoc
     */
    public function sanitizeEmail(string $value): string {
        return sanitize_email($value);
    }

    /**
     * @inheritDoc
     */
    public function sanitizeTitle(string $value): string {
        return sanitize_title($value);
    }

    /**
     * @inheritDoc
     */
    public function sanitizeFileName(string $value): string {
        return sanitize_file_name($value);
    }

    /**
     * @inheritDoc
     */
    public function sanitizeHtmlClass(string $value, string $fallback = ''): string {
        return sanitize_html_class($value, $fallback);
    }

    /**
     * @inheritDoc
     */
    public function escTextarea(string $value): string {
        return esc_textarea($value);
    }

    /**
     * @inheritDoc
     */
    public function escJson(mixed $value): string {
        return esc_js(wp_json_encode($value));
    }
}