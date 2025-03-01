<?php

namespace WPSettingsKit\Infrastructure\Platform\WordPress\Core;

use WPSettingsKit\Infrastructure\Platform\WordPress\Core\Interface\IAdminService;

class WPAdminService implements IAdminService {
    /**
     * @inheritDoc
     */
    public function addMenuPage(
        string $page_title,
        string $menu_title,
        string $capability,
        string $menu_slug,
        callable $function = null,
        string $icon_url = '',
        ?int $position = null
    ): string {
        return add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
    }

    /**
     * @inheritDoc
     */
    public function addSubmenuPage(
        string $parent_slug,
        string $page_title,
        string $menu_title,
        string $capability,
        string $menu_slug,
        callable $function = null,
        ?int $position = null
    ): string|false {
        return add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function, $position);
    }

    /**
     * @inheritDoc
     */
    public function addSettingsSection(string $id, string $title, callable $callback, string $page): void {
        add_settings_section($id, $title, $callback, $page);
    }

    /**
     * @inheritDoc
     */
    public function addSettingsField(
        string $id,
        string $title,
        callable $callback,
        string $page,
        string $section = 'default',
        array $args = []
    ): void {
        add_settings_field($id, $title, $callback, $page, $section, $args);
    }

    /**
     * @inheritDoc
     */
    public function registerSetting(string $option_group, string $option_name, array $args = []): void {
        register_setting($option_group, $option_name, $args);
    }

    /**
     * @inheritDoc
     */
    public function addAdminNotice(string $message, string $type = 'info', bool $dismissible = true): void {
        add_action('admin_notices', function() use ($message, $type, $dismissible) {
            $class = sprintf('notice notice-%s%s',
                $type,
                $dismissible ? ' is-dismissible' : ''
            );
            printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), $message);
        });
    }

    /**
     * @inheritDoc
     */
    public function enqueueScript(string $handle, string $src = '', array $deps = [], string|bool|null $ver = null, bool $in_footer = false): bool {
        return wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
    }

    /**
     * @inheritDoc
     */
    public function enqueueStyle(string $handle, string $src = '', array $deps = [], string|bool|null $ver = null, string $media = 'all'): bool {
        return wp_enqueue_style($handle, $src, $deps, $ver, $media);
    }
}