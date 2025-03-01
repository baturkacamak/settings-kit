<?php

namespace WPSettingsKit\WordPress\Core;

use WPSettingsKit\WordPress\Core\Interface\IUserService;

class WPUserService implements IUserService
{
    /**
     * @inheritDoc
     */
    public function currentUserCan(string $capability, ?int $user_id = null): bool
    {
        if ($user_id !== null) {
            $user = get_user_by('id', $user_id);
            return $user && user_can($user, $capability);
        }
        return current_user_can($capability);
    }

    /**
     * @inheritDoc
     */
    public function getCurrentUserId(): int
    {
        return get_current_user_id();
    }

    /**
     * @inheritDoc
     */
    public function getUserByEmail(string $email): \WP_User|false
    {
        return get_user_by('email', $email);
    }

    /**
     * @inheritDoc
     */
    public function getUserByLogin(string $login): \WP_User|false
    {
        return get_user_by('login', $login);
    }

    /**
     * @inheritDoc
     */
    public function getUserMeta(int $user_id, string $key, bool $single = true): mixed
    {
        return get_user_meta($user_id, $key, $single);
    }

    /**
     * @inheritDoc
     */
    public function updateUserMeta(int $user_id, string $key, mixed $value, mixed $prev_value = ''): bool|int
    {
        return update_user_meta($user_id, $key, $value, $prev_value);
    }

    /**
     * @inheritDoc
     */
    public function deleteUserMeta(int $user_id, string $key, mixed $value = ''): bool
    {
        return delete_user_meta($user_id, $key, $value);
    }

    /**
     * @inheritDoc
     */
    public function userHasRole(int $user_id, string $role): bool
    {
        $user = $this->getUserById($user_id);
        return $user && in_array($role, $user->roles);
    }

    /**
     * @inheritDoc
     */
    public function getUserById(int $user_id): \WP_User|false
    {
        return get_user_by('id', $user_id);
    }
}