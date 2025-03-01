<?php

namespace WPSettingsKit\Infrastructure\Platform\WordPress\Core;

use WPSettingsKit\Infrastructure\Platform\WordPress\Core\Interface\ITaxonomyService;

class WPTaxonomyService implements ITaxonomyService {
    /**
     * @inheritDoc
     */
    public function getTerm(int $term_id, string $taxonomy): \WP_Term|\WP_Error {
        return get_term($term_id, $taxonomy);
    }

    /**
     * @inheritDoc
     */
    public function getTerms(array $args = []): array|\WP_Error {
        return get_terms($args);
    }

    /**
     * @inheritDoc
     */
    public function insertTerm(string $term, string $taxonomy, array $args = []): array|\WP_Error {
        return wp_insert_term($term, $taxonomy, $args);
    }

    /**
     * @inheritDoc
     */
    public function updateTerm(int $term_id, string $taxonomy, array $args = []): array|\WP_Error {
        return wp_update_term($term_id, $taxonomy, $args);
    }

    /**
     * @inheritDoc
     */
    public function deleteTerm(int $term_id, string $taxonomy, array $args = []): bool|\WP_Error {
        return wp_delete_term($term_id, $taxonomy, $args);
    }

    /**
     * @inheritDoc
     */
    public function getTermMeta(int $term_id, string $key, bool $single = true): mixed {
        return get_term_meta($term_id, $key, $single);
    }

    /**
     * @inheritDoc
     */
    public function updateTermMeta(int $term_id, string $key, mixed $value, mixed $prev_value = ''): bool|int {
        return update_term_meta($term_id, $key, $value, $prev_value);
    }

    /**
     * @inheritDoc
     */
    public function deleteTermMeta(int $term_id, string $key, mixed $value = ''): bool {
        return delete_term_meta($term_id, $key, $value);
    }

    /**
     * @inheritDoc
     */
    public function taxonomyExists(string $taxonomy): bool {
        return taxonomy_exists($taxonomy);
    }

    /**
     * @inheritDoc
     */
    public function registerTaxonomy(string $taxonomy, array|string $object_type, array $args = []): \WP_Error|\WP_Taxonomy {
        return register_taxonomy($taxonomy, $object_type, $args);
    }
}