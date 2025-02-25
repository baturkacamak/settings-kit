<?php

namespace WPSettingsKit\Manager;

use WPSettingsKit\Cache\Interface\ISettingsCache;
use WPSettingsKit\Cache\WPSettingsCache;
use WPSettingsKit\Context\Interface\IContextManager;
use WPSettingsKit\Context\WPContextManager;
use WPSettingsKit\Event\EventManager;
use WPSettingsKit\Event\Interface\IFieldEventDispatcher;
use WPSettingsKit\Exception\CacheException;
use WPSettingsKit\Exception\RepositoryException;
use WPSettingsKit\Exception\SettingsException;
use WPSettingsKit\Exception\ValidationException;
use WPSettingsKit\Field\Interface\IField;
use WPSettingsKit\Manager\Interface\ISettingsManager;
use WPSettingsKit\Permission\Interface\IPermissionManager;
use WPSettingsKit\Permission\WPPermissionManager;
use WPSettingsKit\Template\Interface\ITemplateManager;
use WPSettingsKit\Template\WPTemplateManager;
use WPSettingsKit\WordPress\Interface\ISettingsRepository;
use WPSettingsKit\WordPress\WPSettingsAdapter;

/**
 * Main settings manager class
 */
class SettingsManager implements ISettingsManager
{
    private ISettingsRepository $repository;
    private ISettingsCache $cache;
    private IFieldEventDispatcher $eventDispatcher;
    private IPermissionManager $permissionManager;
    private IContextManager $contextManager;
    private ITemplateManager $templateManager;
    private WPSettingsAdapter $wpAdapter;

    private array $fields = [];
    private array $sections = [];
    private bool $initialized = false;

    /**
     * Constructor
     */
    public function __construct(
        ISettingsRepository    $repository,
        ?ISettingsCache        $cache = null,
        ?IFieldEventDispatcher $eventDispatcher = null,
        ?IPermissionManager    $permissionManager = null,
        ?IContextManager       $contextManager = null,
        ?ITemplateManager      $templateManager = null
    )
    {
        $this->repository        = $repository;
        $this->cache             = $cache ?? new WPSettingsCache();
        $this->eventDispatcher   = $eventDispatcher ?? new EventManager();
        $this->permissionManager = $permissionManager ?? new WPPermissionManager();
        $this->contextManager    = $contextManager ?? new WPContextManager();
        $this->templateManager   = $templateManager ?? new WPTemplateManager();

        // Initialize WordPress adapter
        $this->wpAdapter = new WPSettingsAdapter('settings_manager');
    }

    /**
     * Add a field to a section
     */
    public function addField(IField $field, string $section = 'default'): self
    {
        if (!isset($this->sections[$section])) {
            $this->addSection($section, ucfirst($section));
        }

        $this->fields[$field->getKey()] = [
            'field'   => $field,
            'section' => $section,
        ];

        return $this;
    }

    /**
     * Add a new section
     */
    public function addSection(string $id, string $title): self
    {
        $this->sections[$id] = [
            'id'    => $id,
            'title' => $title,
        ];
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        // Register sections
        foreach ($this->sections as $section) {
            $this->wpAdapter->addSettingsSection(
                $section['id'],
                $section['title']
            );
        }

        // Register fields
        foreach ($this->fields as $key => $config) {
            $this->wpAdapter->addSettingsField(
                $config['field'],
                $config['section']
            );
        }

        // Register with WordPress
        $this->wpAdapter->registerSettings();
    }

    /**
     * Saves all fields and their values, providing user feedback on errors.
     *
     * @return bool True if all fields are saved successfully, false if any operation fails.
     * @throws SettingsException If a field cannot be saved due to repository or cache issues.
     */
    public function save(): bool
    {
        $success   = true;
        $allErrors = [];

        foreach ($this->fields as $key => $config) {
            /** @var IField $field */
            $field = $config['field'];
            if (!$this->permissionManager->canEdit($key)) {
                continue;
            }

            try {
                $errors = $field->getValidationErrors();
                if (!empty($errors)) {
                    $allErrors[$key] = $errors;
                    $success         = false;
                    $this->addAdminNotice('error', sprintf(
                        __('Validation failed for field "%s": %s', 'wp-settings-kit'),
                        $field->getLabel(),
                        implode('; ', $errors)
                    ));
                    continue;
                }

                $value = $field->sanitize();
                $this->repository->set($key, $value);
                $this->cache->set($key, $value);
                $this->eventDispatcher->dispatch('field_saved', [
                    'key'   => $key,
                    'value' => $value,
                    'field' => $field,
                ]);
                do_action('wp_settings_field_saved', $key, $value, $field, $this);
            } catch (ValidationException $e) {
                $success = false;
                $this->addAdminNotice('error', $e->getMessage());
            } catch (CacheException $e) {
                $success = false;
                $this->addAdminNotice('error', sprintf(
                    __('Failed to cache field "%s": %s', 'wp-settings-kit'),
                    $key,
                    $e->getMessage()
                ));
                throw new SettingsException("Cache error for {$key}: " . $e->getMessage(), 0, $e);
            } catch (RepositoryException $e) {
                $success = false;
                $this->addAdminNotice('error', sprintf(
                    __('Failed to save field "%s" to repository: %s', 'wp-settings-kit'),
                    $key,
                    $e->getMessage()
                ));
                throw new SettingsException("Repository error for {$key}: " . $e->getMessage(), 0, $e);
            }
        }

        if (!empty($allErrors)) {
            $allErrors = apply_filters('wp_settings_manager_save_errors', $allErrors, $this);
            do_action('wp_settings_manager_save_failed', $allErrors, $this);
        } elseif ($success) {
            $this->addAdminNotice('success', __('Settings saved successfully.', 'wp-settings-kit'));
        }

        return $success;
    }

    /**
     * Adds an admin notice to be displayed in the WordPress admin panel.
     *
     * @param string $type The type of notice ('error', 'success', 'warning', 'info').
     * @param string $message The message to display.
     * @return void
     */
    private function addAdminNotice(string $type, string $message): void
    {
        add_action('admin_notices', function () use ($type, $message) {
            $class = "notice notice-{$type}";
            printf('<div class="%s"><p>%s</p></div>', esc_attr($class), esc_html($message));
        });
        do_action('wp_settings_manager_admin_notice_added', $type, $message, $this);
    }

    /**
     * @inheritDoc
     * @throws SettingsException
     */
    public function getValue(string $key): mixed
    {
        if (!isset($this->fields[$key])) {
            throw new SettingsException("Field not found: {$key}");
        }
        $field = $this->fields[$key]['field'];
        if (!$this->permissionManager->canView($key)) {
            throw new SettingsException("Permission denied to view field: {$key}");
        }
        return $field->getValue();
    }

    /**
     * Get all fields
     *
     * @return array<IField>
     */
    public function getFields(): array
    {
        return array_map(
            fn($config) => $config['field'],
            $this->fields
        );
    }

    /**
     * Get fields in a section
     */
    public function getFieldsInSection(string $section): array
    {
        return array_filter(
            $this->fields,
            fn($config) => $config['section'] === $section
        );
    }

    /**
     * Save current settings as a template
     */
    public function saveAsTemplate(string $name): bool
    {
        return $this->templateManager->saveAsTemplate($name);
    }

    /**
     * Load settings from a template
     */
    public function loadTemplate(string $name): bool
    {
        if ($this->templateManager->loadTemplate($name)) {
            $this->initialized = false;
            $this->initializeFields();
            return true;
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function initializeFields(): void
    {
        if ($this->initialized) {
            return;
        }

        foreach ($this->fields as $key => $config) {
            $field = $config['field'];

            // Check permissions
            if (!$this->permissionManager->canView($key)) {
                continue;
            }

            // Get value from cache or repository
            $value = $this->cache->get($key);
            if ($value === null) {
                $value = $this->repository->get($key);
                $this->cache->set($key, $value);
            }

            // Apply context
            $value = $this->contextManager->getContextualValue($field);

            // Set field value
            $field->setValue($value);
        }

        $this->initialized = true;
    }

    /**
     * @inheritDoc
     */
    public function setValue(string $key, mixed $value): void
    {
        if (!isset($this->fields[$key])) {
            return;
        }

        $field = $this->fields[$key]['field'];

        if (!$this->permissionManager->canEdit($key)) {
            return;
        }

        $field->setValue($value);
    }

    /**
     * Get available templates
     */
    public function getTemplates(): array
    {
        return $this->templateManager->getTemplates();
    }
}
