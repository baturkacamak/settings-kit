<?php

namespace WPSettingsKit\Infrastructure\Platform\WordPress\Core\Interface;

/**
 * Interface for WordPress admin functions.
 */
interface IAdminService {
    /**
     * Adds menu page to the admin dashboard.
     *
     * @param string $page_title The text to be displayed in the title tags of the page
     * @param string $menu_title The text to be used for the menu
     * @param string $capability The capability required for this menu to be displayed to the user
     * @param string $menu_slug The slug name to refer to this menu by
     * @param callable|null $function The function to be called to output the content for this page
     * @param string $icon_url The URL to the icon to be used for this menu
     * @param int|null $position The position in the menu order this item should appear
     * @return string The resulting page hook name
     */
    public function addMenuPage(
        string $page_title,
        string $menu_title,
        string $capability,
        string $menu_slug,
        callable $function = null,
        string $icon_url = '',
        ?int $position = null
    ): string;

    /**
     * Adds submenu page to an existing menu.
     *
     * @param string $parent_slug The slug name for the parent menu
     * @param string $page_title The text to be displayed in the title tags of the page
     * @param string $menu_title The text to be used for the menu
     * @param string $capability The capability required for this menu to be displayed to the user
     * @param string $menu_slug The slug name to refer to this menu by
     * @param callable|null $function The function to be called to output the content for this page
     * @param int|null $position The position in the menu order this item should appear
     * @return string|false The resulting page hook name, or false if the menu does not exist
     */
    public function addSubmenuPage(
        string $parent_slug,
        string $page_title,
        string $menu_title,
        string $capability,
        string $menu_slug,
        callable $function = null,
        ?int $position = null
    ): string|false;

    /**
     * Registers a settings section.
     *
     * @param string $id Slug-name to identify the section
     * @param string $title Formatted title of the section
     * @param callable $callback Function that fills the section with the desired content
     * @param string $page The slug-name of the settings page on which to show the section
     * @return void
     */
    public function addSettingsSection(string $id, string $title, callable $callback, string $page): void;

    /**
     * Registers a settings field.
     *
     * @param string $id Slug-name to identify the field
     * @param string $title Formatted title of the field
     * @param callable $callback Function that fills the field with the desired inputs
     * @param string $page The slug-name of the settings page on which to show the field
     * @param string $section The slug-name of the section of the settings page in which to show the field
     * @param array $args Additional arguments that get passed to the callback function
     * @return void
     */
    public function addSettingsField(
        string $id,
        string $title,
        callable $callback,
        string $page,
        string $section = 'default',
        array $args = []
    ): void;

    /**
     * Registers a setting.
     *
     * @param string $option_group A settings group name
     * @param string $option_name The name of an option to sanitize and save
     * @param array $args Data used to describe the setting when registered
     * @return void
     */
    public function registerSetting(string $option_group, string $option_name, array $args = []): void;

    /**
     * Adds an admin notice to the page.
     *
     * @param string $message The notice message
     * @param string $type The notice type (error, warning, success, info)
     * @param bool $dismissible Whether the notice should be dismissible
     * @return void
     */
    public function addAdminNotice(string $message, string $type = 'info', bool $dismissible = true): void;

    /**
     * Enqueues a script in the admin.
     *
     * @param string $handle Name of the script
     * @param string $src URL to the script
     * @param array $deps Array of registered script handles this script depends on
     * @param string|bool|null $ver Script version
     * @param bool $in_footer Whether to enqueue the script before </body> instead of in the <head>
     * @return bool True if the script was successfully registered and enqueued, false otherwise
     */
    public function enqueueScript(string $handle, string $src = '', array $deps = [], string|bool|null $ver = null, bool $in_footer = false): bool;

    /**
     * Enqueues a stylesheet in the admin.
     *
     * @param string $handle Name of the stylesheet
     * @param string $src URL to the stylesheet
     * @param array $deps Array of registered stylesheet handles this stylesheet depends on
     * @param string|bool|null $ver Stylesheet version
     * @param string $media The media for which this stylesheet has been defined
     * @return bool True if the stylesheet was successfully registered and enqueued, false otherwise
     */
    public function enqueueStyle(string $handle, string $src = '', array $deps = [], string|bool|null $ver = null, string $media = 'all'): bool;
}