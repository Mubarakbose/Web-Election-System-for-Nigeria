<?php

class MessageService extends BaseService
{
    public function getTableName(): string
    {
        return 'message';
    }

    /**
     * Get all messages with pagination
     * 
     * @param Pagination|null $pagination Pagination object (optional)
     * @return array Array of messages
     */
    public function getAll(?Pagination $pagination = null): array
    {
        $sql = "SELECT * FROM message ORDER BY MessageID DESC";

        if ($pagination) {
            $sql .= " " . $pagination->getSqlLimit();
        }

        try {
            $stmt = db_query($sql);
            return db_fetch_all($stmt) ?? [];
        } catch (Exception $e) {
            ErrorHandler::log('MessageService::getAll', $e->getMessage());
            return [];
        }
    }

    /**
     * Get count of all messages
     * 
     * @return int Total count
     */
    public function getCount(): int
    {
        $sql = "SELECT COUNT(*) as total FROM message";

        try {
            $stmt = db_query($sql);
            $result = db_fetch_assoc($stmt);
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            ErrorHandler::log('MessageService::getCount', $e->getMessage());
            return 0;
        }
    }
}
