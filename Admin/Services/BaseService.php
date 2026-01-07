<?php

/**
 * BaseService
 * Abstract base class for all service classes.
 * Provides common patterns for database operations with error handling.
 */

abstract class BaseService
{
    protected string $tableName;

    /**
     * Subclasses must define the table name.
     */
    abstract public function getTableName(): string;

    /**
     * Get all records from the table with optional pagination.
     * 
     * @param Pagination|null $pagination Pagination object (optional)
     * @return array Array of records
     */
    public function getAll(?Pagination $pagination = null): array
    {
        $sql = "SELECT * FROM " . $this->getTableName();

        if ($pagination) {
            $sql .= " " . $pagination->getSqlLimit();
        }

        try {
            $stmt = db_query($sql);
            return db_fetch_all($stmt) ?? [];
        } catch (Exception $e) {
            ErrorHandler::log($e, get_class($this) . '::getAll', false);
            return [];
        }
    }

    /**
     * Get total count of records in the table.
     * 
     * @return int Total count
     */
    public function getCount(): int
    {
        $sql = "SELECT COUNT(*) as total FROM " . $this->getTableName();

        try {
            $stmt = db_query($sql);
            $result = db_fetch_assoc($stmt);
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            ErrorHandler::log($e, get_class($this) . '::getCount', false);
            return 0;
        }
    }

    /**
     * Get a single record by ID (assumes 'ID' field or custom ID field).
     * 
     * @param int $id The ID value
     * @param string $idField Field name (default: {TableName}ID or id)
     * @return array|null The record or null if not found
     */
    public function getById(int $id, string $idField = ''): ?array
    {
        if (empty($idField)) {
            // Try to guess the ID field: ContestantID, StaffID, UserID, etc.
            $idField = ucfirst($this->getTableName()) . 'ID';
        }

        $sql = "SELECT * FROM " . $this->getTableName() . " WHERE " . $idField . " = :id";

        try {
            $stmt = db_query($sql, [':id' => $id]);
            return db_fetch_assoc($stmt);
        } catch (Exception $e) {
            ErrorHandler::log($e, get_class($this) . '::getById', false);
            return null;
        }
    }

    /**
     * Delete a record by ID.
     * 
     * @param int $id The ID value
     * @param string $idField Field name (auto-detected if empty)
     * @return bool True if successful
     */
    public function deleteById(int $id, string $idField = ''): bool
    {
        if (empty($idField)) {
            $idField = ucfirst($this->getTableName()) . 'ID';
        }

        $sql = "DELETE FROM " . $this->getTableName() . " WHERE " . $idField . " = :id";

        try {
            db_query($sql, [':id' => $id]);
            return true;
        } catch (Exception $e) {
            ErrorHandler::log($e, get_class($this) . '::deleteById', false);
            return false;
        }
    }
}
