<?php

namespace   WPSettingsKit\Infrastructure\Permission\Interface;

interface IPermissionManager {
    /**
     * Check if current user can view a field
     *
     * @param string $fieldKey Field key
     * @return bool True if can view, false otherwise
     */
    public function canView(string $fieldKey): bool;

    /**
     * Check if current user can edit a field
     *
     * @param string $fieldKey Field key
     * @return bool True if can edit, false otherwise
     */
    public function canEdit(string $fieldKey): bool;

    /**
     * Check if current user has access to perform an action
     *
     * @param string $fieldKey Field key
     * @param string $action Action to check
     * @return bool True if has access, false otherwise
     */
    public function checkAccess(string $fieldKey, string $action): bool;
}
