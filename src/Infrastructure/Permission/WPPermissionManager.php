<?php

namespace WPSettingsKit\Infrastructure\Permission;

use WPSettingsKit\Infrastructure\Permission\Interface\IPermissionManager;

/**
 * WordPress-specific permission manager implementation
 */
class WPPermissionManager implements IPermissionManager {
    private string $capability;

    public function __construct(string $capability = 'manage_options') {
        $this->capability = $capability;
    }

    /**
     * @inheritDoc
     */
    public function canView(string $fieldKey): bool {
        return $this->checkCapability('view', $fieldKey);
    }

    /**
     * @inheritDoc
     */
    public function canEdit(string $fieldKey): bool {
        return $this->checkCapability('edit', $fieldKey);
    }

    /**
     * @inheritDoc
     */
    public function checkAccess(string $fieldKey, string $action): bool {
        return $this->checkCapability($action, $fieldKey);
    }

    /**
     * Check if current user has the required capability
     */
    private function checkCapability(string $action, string $fieldKey): bool {
        $capability = apply_filters(
            'settings_manager_field_capability',
            $this->capability,
            $action,
            $fieldKey
        );

        return current_user_can($capability);
    }
}
