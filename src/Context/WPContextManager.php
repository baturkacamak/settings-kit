<?php

namespace WPSettingsKit\Context;

use  WPSettingsKit\Context\Interface\IContextManager;
use  WPSettingsKit\Field\Interface\IField;

/**
 * WordPress-specific context manager implementation
 */
class WPContextManager implements IContextManager {
    private string $context = '';

    /**
     * @inheritDoc
     */
    public function getContext(): string {
        return $this->context;
    }

    /**
     * @inheritDoc
     */
    public function setContext(string $context): void {
        $this->context = $context;
    }

    /**
     * @inheritDoc
     */
    public function getContextualValue(IField $field): mixed {
        $value = $field->getValue();

        switch ($this->context) {
            case 'network':
                return get_site_option($field->getKey(), $value);

            case 'user':
                return get_user_meta(get_current_user_id(), $field->getKey(), true) ?: $value;

            case 'post':
                global $post;
                return $post ? get_post_meta($post->ID, $field->getKey(), true) : $value;

            default:
                return apply_filters(
                    'settings_manager_contextual_value',
                    $value,
                    $field,
                    $this->context
                );
        }
    }
}
