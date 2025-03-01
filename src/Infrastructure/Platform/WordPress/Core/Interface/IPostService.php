<?php

namespace WPSettingsKit\Infrastructure\Platform\WordPress\Core\Interface;

/**
 * Interface for WordPress post functions.
 */
interface IPostService {
    /**
     * Gets a post by ID.
     *
     * @param int $post_id Post ID
     * @param string $output Optional. The required return type (OBJECT, ARRAY_A, or ARRAY_N), defaults to OBJECT
     * @return \WP_Post|array|null Post object/array if found, null otherwise
     */
    public function getPost(int $post_id, string $output = 'OBJECT'): \WP_Post|array|null;

    /**
     * Gets posts based on query parameters.
     *
     * @param array $args Query arguments
     * @return array Array of posts
     */
    public function getPosts(array $args = []): array;

    /**
     * Inserts or updates a post.
     *
     * @param array $postarr Post data
     * @param bool $wp_error Whether to return WP_Error on failure
     * @param bool $fire_after_hooks Whether to fire after insert hooks
     * @return int|\WP_Error Post ID on success, WP_Error on failure
     */
    public function insertPost(array $postarr, bool $wp_error = false, bool $fire_after_hooks = true): int|\WP_Error;

    /**
     * Updates a post.
     *
     * @param array $postarr Post data
     * @param bool $wp_error Whether to return WP_Error on failure
     * @param bool $fire_after_hooks Whether to fire after update hooks
     * @return int|\WP_Error Post ID on success, WP_Error on failure
     */
    public function updatePost(array $postarr, bool $wp_error = false, bool $fire_after_hooks = true): int|\WP_Error;

    /**
     * Deletes a post.
     *
     * @param int $post_id Post ID
     * @param bool $force_delete Whether to bypass trash and delete permanently
     * @return \WP_Post|false|null Post data on success, false or null on failure
     */
    public function deletePost(int $post_id, bool $force_delete = false): \WP_Post|false|null;

    /**
     * Gets post meta.
     *
     * @param int $post_id Post ID
     * @param string $key Meta key
     * @param bool $single Whether to return a single value
     * @return mixed Meta value
     */
    public function getPostMeta(int $post_id, string $key, bool $single = true): mixed;

    /**
     * Updates post meta.
     *
     * @param int $post_id Post ID
     * @param string $key Meta key
     * @param mixed $value Meta value
     * @param mixed $prev_value Previous value to check before updating
     * @return bool|int Meta ID if the key didn't exist, true on successful update, false on failure
     */
    public function updatePostMeta(int $post_id, string $key, mixed $value, mixed $prev_value = ''): bool|int;

    /**
     * Deletes post meta.
     *
     * @param int $post_id Post ID
     * @param string $key Meta key
     * @param mixed $value Meta value to delete
     * @return bool True on success, false on failure
     */
    public function deletePostMeta(int $post_id, string $key, mixed $value = ''): bool;

    /**
     * Gets post terms for a taxonomy.
     *
     * @param int $post_id Post ID
     * @param string $taxonomy Taxonomy name
     * @param array $args Query arguments
     * @return array|\WP_Error Array of WP_Term objects on success, WP_Error on failure
     */
    public function getPostTerms(int $post_id, string $taxonomy, array $args = []): array|\WP_Error;

    /**
     * Sets post terms for a taxonomy.
     *
     * @param int $post_id Post ID
     * @param array $terms Array of term IDs or names
     * @param string $taxonomy Taxonomy name
     * @param bool $append Whether to append to existing terms
     * @return array|\WP_Error Array of term IDs on success, WP_Error on failure
     */
    public function setPostTerms(int $post_id, array $terms, string $taxonomy, bool $append = false): array|\WP_Error;
}