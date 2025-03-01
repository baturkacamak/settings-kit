<?php

namespace WPSettingsKit\WordPress\Core\Interface;

interface IUserService {
    public function currentUserCan(string $capability, ?int $user_id = null): bool;
    public function getCurrentUserId(): int;
}