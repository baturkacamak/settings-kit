<?php

namespace WPSettingsKit\Infrastructure\Platform\WordPress\Core;

use WPSettingsKit\Infrastructure\Platform\WordPress\Core\Interface\IPostService;

class WPPostService implements IPostService {
    /**
     * @inheritDoc
     */
    public function getPost(int $post_id, string $output = 'OBJECT'): \WP_Post|array|null {
        return get_post($post_id, $output);
    }

    /**
     * @inheritDoc
     */
    public function getPosts(array $args = []): array {
        return get_posts($args);
    }

    /**
     * @inheritDoc
     */
    public function insertPost(array $postarr, bool $wp_error = false, bool $fire_after_hooks = true): int|\WP_Error {
        return wp_insert_post($postarr, $wp_error, $fire_after_hooks);
    }

    /**
     * @inheritDoc
     */
    public function updatePost(array $postarr, bool $wp_error = false, bool $fire_after_hooks = true): int|\WP_Error {
        return wp_update_post($postarr, $wp_error, $fire_after_hooks);
    }

    /**
     * @inheritDoc
     */
    public function deletePost(int $post_id, bool $force_delete = false): \WP_Post|false|null {
        return wp_delete_post($post_id, $force_delete);
    }

    /**
     * @inheritDoc
     */
    public function getPostMeta(int $post_id, string $key, bool $single = true): mixed {
        return get_post_meta($post_id, $key, $single);
    }

    /**
     * @inheritDoc
     */
    public function updatePostMeta(int $post_id, string $key, mixed $value, mixed $prev_value = ''): bool|int {
        return update_post_meta($post_id, $key, $value, $prev_value);
    }

    /**
     * @inheritDoc
     */
    public function deletePostMeta(int $post_id, string $key, mixed $value = ''): bool {
        return delete_post_meta($post_id, $key, $value);
    }

    /**
     * @inheritDoc
     */
    public function getPostTerms(int $post_id, string $taxonomy, array $args = []): array|\WP_Error {
        return wp_get_post_terms($post_id, $taxonomy, $args);
    }

    /**
     * @inheritDoc
     */
    public function setPostTerms(int $post_id, array $terms, string $taxonomy, bool $append = false): array|\WP_Error {
        return wp_set_post_terms($post_id, $terms, $taxonomy, $append);
    }
}