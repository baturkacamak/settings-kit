<?php

namespace WPSettingsKit\Infrastructure\Platform\WordPress\Core\Interface;

interface ISanitizationService {
    public function sanitizeTextField(string|array $value): string|array;
    public function sanitizeTextarea(string $value): string;
    public function escAttr(string $value): string;
    public function escHtml(string $value): string;
    public function escUrl(string $value): string;
}