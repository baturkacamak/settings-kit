<?php

namespace WPSettingsKit\Infrastructure\Platform\WordPress\Core\Interface;

use WPSettingsKit\WordPress\Core\Interface\WP_Error;

/**
 * Interface for WordPress media functions.
 */
interface IMediaService {
    /**
     * Gets an attachment by ID.
     *
     * @param int $attachment_id Attachment ID
     * @return array|\WP_Post|null Attachment post object/array if found, null otherwise
     */
    public function getAttachment(int $attachment_id): array|\WP_Post|null;

    /**
     * Gets attachment URL.
     *
     * @param int $attachment_id Attachment ID
     * @return string|false Attachment URL or false on failure
     */
    public function getAttachmentUrl(int $attachment_id): string|false;

    /**
     * Gets attachment image source.
     *
     * @param int $attachment_id Attachment ID
     * @param string|array $size Registered image size or custom size array
     * @param bool $icon Whether to treat image as an icon
     * @return array|false Array with url, width, height, or false on failure
     */
    public function getAttachmentImageSrc(int $attachment_id, string|array $size = 'thumbnail', bool $icon = false): array|false;

    /**
     * Gets attachment image HTML.
     *
     * @param int $attachment_id Attachment ID
     * @param string|array $size Registered image size or custom size array
     * @param bool $icon Whether to treat image as an icon
     * @param array $attr HTML attributes for the img tag
     * @return string HTML img element or empty string on failure
     */
    public function getAttachmentImage(int $attachment_id, string|array $size = 'thumbnail', bool $icon = false, array $attr = []): string;

    /**
     * Inserts an attachment.
     *
     * @param array $args Attachment arguments
     * @return int|\WP_Error Attachment ID on success, WP_Error on failure
     */
    public function insertAttachment(array $args): int|\WP_Error;

    /**
     * Uploads a file.
     *
     * @param array $file $_FILES array element
     * @param int $post_id Parent post ID, or 0 for no parent
     * @param string $desc Description
     * @param array $args Additional arguments
     * @return array|WP_Error Attachment data on success, WP_Error on failure
     */
    public function uploadFile(array $file, int $post_id = 0, string $desc = '', array $args = []): array|\WP_Error;

    /**
     * Sets post thumbnail.
     *
     * @param int $post_id Post ID
     * @param int $attachment_id Attachment ID
     * @return bool True on success, false on failure
     */
    public function setPostThumbnail(int $post_id, int $attachment_id): bool;

    /**
     * Gets post thumbnail ID.
     *
     * @param int $post_id Post ID
     * @return int|false Thumbnail ID or false if not set
     */
    public function getPostThumbnailId(int $post_id): int|false;

    /**
     * Checks if a post has a thumbnail.
     *
     * @param int $post_id Post ID
     * @return bool True if the post has a thumbnail, false otherwise
     */
    public function hasPostThumbnail(int $post_id): bool;

    /**
     * Gets post thumbnail HTML.
     *
     * @param int $post_id Post ID
     * @param string|array $size Registered image size or custom size array
     * @param array $attr HTML attributes for the img tag
     * @return string HTML img element or empty string on failure
     */
    public function getPostThumbnail(int $post_id, string|array $size = 'post-thumbnail', array $attr = []): string;

    /**
     * Gets image sizes.
     *
     * @return array Array of registered image sizes
     */
    public function getImageSizes(): array;
}