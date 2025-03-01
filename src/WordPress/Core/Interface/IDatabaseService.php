<?php

namespace WPSettingsKit\WordPress\Core\Interface;

/**
 * Interface for WordPress database functions.
 */
interface IDatabaseService {
    /**
     * Executes an SQL query.
     *
     * @param string $query SQL query
     * @return int|bool Number of rows affected/selected or false on error
     */
    public function query(string $query): int|bool;

    /**
     * Prepares a SQL query for safe execution.
     *
     * @param string $query SQL query with placeholders
     * @param mixed ...$args Values to replace placeholders
     * @return string|false Prepared query or false on error
     */
    public function prepare(string $query, mixed ...$args): string|false;

    /**
     * Retrieves an entire row from a table.
     *
     * @param string $query SQL query
     * @param string $output The required return type (OBJECT, ARRAY_A, or ARRAY_N)
     * @return object|array|null Database query result or null on failure
     */
    public function getRow(string $query, string $output = 'OBJECT'): object|array|null;

    /**
     * Retrieves multiple rows from a table.
     *
     * @param string $query SQL query
     * @param string $output The required return type (OBJECT, ARRAY_A, or ARRAY_N)
     * @return array Array of row objects or empty array on failure
     */
    public function getResults(string $query, string $output = 'OBJECT'): array;

    /**
     * Retrieves a single column from a table.
     *
     * @param string $query SQL query
     * @param int $column Column offset
     * @return array Array of column values or empty array on failure
     */
    public function getCol(string $query, int $column = 0): array;

    /**
     * Retrieves a single variable from a table.
     *
     * @param string $query SQL query
     * @param int $x Column offset
     * @param int $y Row offset
     * @return mixed Variable value or null on failure
     */
    public function getVar(string $query, int $x = 0, int $y = 0): mixed;

    /**
     * Inserts a row into a table.
     *
     * @param string $table Table name
     * @param array $data Data to insert (column => value)
     * @param array|string $format Format for each column (string or array of strings)
     * @return int|false Number of rows inserted or false on error
     */
    public function insert(string $table, array $data, array|string $format = null): int|false;

    /**
     * Updates a row in a table.
     *
     * @param string $table Table name
     * @param array $data Data to update (column => value)
     * @param array $where Where clause data (column => value)
     * @param array|string $format Format for each column in $data (string or array of strings)
     * @param array|string $where_format Format for each column in $where (string or array of strings)
     * @return int|false Number of rows updated or false on error
     */
    public function update(
        string $table,
        array $data,
        array $where,
        array|string $format = null,
        array|string $where_format = null
    ): int|false;

    /**
     * Deletes a row from a table.
     *
     * @param string $table Table name
     * @param array $where Where clause data (column => value)
     * @param array|string $where_format Format for each column in $where (string or array of strings)
     * @return int|false Number of rows deleted or false on error
     */
    public function delete(string $table, array $where, array|string $where_format = null): int|false;

    /**
     * Gets WordPress prefix.
     *
     * @return string WordPress database prefix
     */
    public function getPrefix(): string;

    /**
     * Performs a database transaction.
     *
     * @param callable $callback Callback function containing SQL queries
     * @return mixed The return value of the callback
     * @throws \Exception If transaction fails
     */
    public function transaction(callable $callback): mixed;
}