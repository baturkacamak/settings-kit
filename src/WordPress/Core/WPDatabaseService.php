<?php

namespace WPSettingsKit\WordPress\Core;

use WPSettingsKit\WordPress\Core\Interface\IDatabaseService;

class WPDatabaseService implements IDatabaseService {
    /**
     * @inheritDoc
     */
    public function query(string $query): int|bool {
        global $wpdb;
        return $wpdb->query($query);
    }

    /**
     * @inheritDoc
     */
    public function prepare(string $query, mixed ...$args): string|false {
        global $wpdb;
        return $wpdb->prepare($query, ...$args);
    }

    /**
     * @inheritDoc
     */
    public function getRow(string $query, string $output = 'OBJECT'): object|array|null {
        global $wpdb;
        return $wpdb->get_row($query, $output);
    }

    /**
     * @inheritDoc
     */
    public function getResults(string $query, string $output = 'OBJECT'): array {
        global $wpdb;
        return $wpdb->get_results($query, $output);
    }

    /**
     * @inheritDoc
     */
    public function getCol(string $query, int $column = 0): array {
        global $wpdb;
        return $wpdb->get_col($query, $column);
    }

    /**
     * @inheritDoc
     */
    public function getVar(string $query, int $x = 0, int $y = 0): mixed {
        global $wpdb;
        return $wpdb->get_var($query, $x, $y);
    }

    /**
     * @inheritDoc
     */
    public function insert(string $table, array $data, array|string $format = null): int|false {
        global $wpdb;
        return $wpdb->insert($table, $data, $format);
    }

    /**
     * @inheritDoc
     */
    public function update(
        string $table,
        array $data,
        array $where,
        array|string $format = null,
        array|string $where_format = null
    ): int|false {
        global $wpdb;
        return $wpdb->update($table, $data, $where, $format, $where_format);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $table, array $where, array|string $where_format = null): int|false {
        global $wpdb;
        return $wpdb->delete($table, $where, $where_format);
    }

    /**
     * @inheritDoc
     */
    public function getPrefix(): string {
        global $wpdb;
        return $wpdb->prefix;
    }

    /**
     * @inheritDoc
     */
    public function transaction(callable $callback): mixed {
        global $wpdb;

        $wpdb->query('START TRANSACTION');

        try {
            $result = $callback($this);
            $wpdb->query('COMMIT');
            return $result;
        } catch (\Exception $e) {
            $wpdb->query('ROLLBACK');
            throw $e;
        }
    }
}