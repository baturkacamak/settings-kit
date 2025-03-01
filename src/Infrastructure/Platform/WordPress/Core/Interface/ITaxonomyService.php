<?php

namespace WPSettingsKit\Infrastructure\Platform\WordPress\Core\Interface;

/**
 * Interface for WordPress taxonomy functions.
 */
interface ITaxonomyService {
    /**
     * Gets a term by ID.
     *
     * @param int $term_id Term ID
     * @param string $taxonomy Taxonomy name
     * @return \WP_Term|\WP_Error Term object on success, WP_Error on failure
     */
    public function getTerm(int $term_id, string $taxonomy): \WP_Term|\WP_Error;

    /**
     * Gets terms based on query parameters.
     *
     * @param array $args Query arguments
     * @return array|\WP_Error Array of WP_Term objects on success, WP_Error on failure
     */
    public function getTerms(array $args = []): array|\WP_Error;

    /**
     * Inserts a term.
     *
     * @param string $term The term name
     * @param string $taxonomy The taxonomy name
     * @param array $args Term arguments
     * @return array|\WP_Error Array with term_id and term_taxonomy_id on success, WP_Error on failure
     */
    public function insertTerm(string $term, string $taxonomy, array $args = []): array|\WP_Error;

    /**
     * Updates a term.
     *
     * @param int $term_id Term ID
     * @param string $taxonomy Taxonomy name
     * @param array $args Term arguments
     * @return array|\WP_Error Array with term_id and term_taxonomy_id on success, WP_Error on failure
     */
    public function updateTerm(int $term_id, string $taxonomy, array $args = []): array|\WP_Error;

    /**
     * Deletes a term.
     *
     * @param int $term_id Term ID
     * @param string $taxonomy Taxonomy name
     * @param array $args Term arguments
     * @return bool|\WP_Error True on success, WP_Error on failure
     */
    public function deleteTerm(int $term_id, string $taxonomy, array $args = []): bool|\WP_Error;

    /**
     * Gets term meta.
     *
     * @param int $term_id Term ID
     * @param string $key Meta key
     * @param bool $single Whether to return a single value
     * @return mixed Meta value
     */
    public function getTermMeta(int $term_id, string $key, bool $single = true): mixed;

    /**
     * Updates term meta.
     *
     * @param int $term_id Term ID
     * @param string $key Meta key
     * @param mixed $value Meta value
     * @param mixed $prev_value Previous value to check before updating
     * @return bool|int Meta ID if the key didn't exist, true on successful update, false on failure
     */
    public function updateTermMeta(int $term_id, string $key, mixed $value, mixed $prev_value = ''): bool|int;

    /**
     * Deletes term meta.
     *
     * @param int $term_id Term ID
     * @param string $key Meta key
     * @param mixed $value Meta value to delete
     * @return bool True on success, false on failure
     */
    public function deleteTermMeta(int $term_id, string $key, mixed $value = ''): bool;

    /**
     * Checks if a taxonomy exists.
     *
     * @param string $taxonomy Taxonomy name
     * @return bool True if taxonomy exists, false otherwise
     */
    public function taxonomyExists(string $taxonomy): bool;

    /**
     * Registers a taxonomy.
     *
     * @param string $taxonomy Taxonomy name
     * @param array|string $object_type Object type or array of object types
     * @param array $args Taxonomy arguments
     * @return \WP_Error|\WP_Taxonomy WP_Taxonomy object on success, WP_Error on failure
     */
    public function registerTaxonomy(string $taxonomy, array|string $object_type, array $args = []): \WP_Error|\WP_Taxonomy;
}