<?php

namespace WPSettingsKit\WordPress\Core;

use WPSettingsKit\WordPress\Core\Interface\IMediaService;

class WPMediaService implements IMediaService {
    /**
     * @inheritDoc
     */
    public function getAttachment(int $attachment_id): array|\WP_Post|null {
        return wp_get_attachment_metadata($attachment_id);
    }

    /**
     * @inheritDoc
     */
    public function getAttachmentUrl(int $attachment_id): string|false {
        return wp_get_attachment_url($attachment_id);
    }

    /**
     * @inheritDoc
     */
    public function getAttachmentImageSrc(int $attachment_id, string|array $size = 'thumbnail', bool $icon = false): array|false {
        return wp_get_attachment_image_src($attachment_id, $size, $icon);
    }

    /**
     * @inheritDoc
     */
    public function getAttachmentImage(int $attachment_id, string|array $size = 'thumbnail', bool $icon = false, array $attr = []): string {
        return wp_get_attachment_image($attachment_id, $size, $icon, $attr);
    }

    /**
     * @inheritDoc
     */
    public function insertAttachment(array $args): int|\WP_Error {
        return wp_insert_attachment($args);
    }

    /**
     * @inheritDoc
     */
    public function uploadFile(array $file, int $post_id = 0, string $desc = '', array $args = []): array|\WP_Error {
        return wp_handle_upload($file, $args);
    }

    /**
     * @inheritDoc
     */
    public function setPostThumbnail(int $post_id, int $attachment_id): bool {
        return set_post_thumbnail($post_id, $attachment_id);
    }

    /**
     * @inheritDoc
     */
    public function getPostThumbnailId(int $post_id): int|false {
        return get_post_thumbnail_id($post_id);
    }

    /**
     * @inheritDoc
     */
    public function hasPostThumbnail(int $post_id): bool {
        return has_post_thumbnail($post_id);
    }

    /**
     * @inheritDoc
     */
    public function getPostThumbnail(int $post_id, string|array $size = 'post-thumbnail', array $attr = []): string {
        return get_the_post_thumbnail($post_id, $size, $attr);
    }

    /**
     * @inheritDoc
     */
    public function getImageSizes(): array {
        global $_wp_additional_image_sizes;
        $sizes = [];

        foreach (get_intermediate_image_sizes() as $size) {
            if (in_array($size, ['thumbnail', 'medium', 'medium_large', 'large'])) {
                $sizes[$size] = [
                    'width' => get_option("{$size}_size_w"),
                    'height' => get_option("{$size}_size_h"),
                    'crop' => get_option("{$size}_crop"),
                ];
            } elseif (isset($_wp_additional_image_sizes[$size])) {
                $sizes[$size] = $_wp_additional_image_sizes[$size];
            }
        }

        return $sizes;
    }
}