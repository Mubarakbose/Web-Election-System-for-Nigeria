<?php

class NewsService extends BaseService
{
    public function getTableName(): string
    {
        return 'news';
    }

    /**
     * Create a new news item
     */
    public function create(array $data): array
    {
        try {
            if (empty($data['NewsTittle']) || empty($data['NewsBody'])) {
                return ['success' => false, 'error' => 'Title and Body are required'];
            }

            $sql = "INSERT INTO news (NewsTittle, NewsBody) VALUES (:title, :body)";
            $params = [
                ':title' => $data['NewsTittle'],
                ':body' => $data['NewsBody'],
            ];

            db_query($sql, $params);
            return ['success' => true, 'message' => 'News created successfully'];
        } catch (Exception $e) {
            ErrorHandler::log('NewsService::create', $e->getMessage());
            return ['success' => false, 'error' => 'Failed to create news'];
        }
    }

    /**
     * Update existing news item
     */
    public function update(int $id, array $data): array
    {
        try {
            if (empty($id)) {
                return ['success' => false, 'error' => 'Invalid News ID'];
            }

            if (empty($data['NewsTittle']) || empty($data['NewsBody'])) {
                return ['success' => false, 'error' => 'Title and Body are required'];
            }

            $sql = "UPDATE news SET NewsTittle = :title, NewsBody = :body WHERE NewsID = :id";
            $params = [
                ':title' => $data['NewsTittle'],
                ':body' => $data['NewsBody'],
                ':id' => $id,
            ];

            db_query($sql, $params);
            return ['success' => true, 'message' => 'News updated successfully'];
        } catch (Exception $e) {
            ErrorHandler::log('NewsService::update', $e->getMessage());
            return ['success' => false, 'error' => 'Failed to update news'];
        }
    }

    /**
     * Get all news items
     */
    public function getAllNews(): array
    {
        try {
            $sql = "SELECT * FROM news ORDER BY NewsID DESC";
            $result = db_query($sql);
            return db_fetch_all($result) ?? [];
        } catch (Exception $e) {
            ErrorHandler::log('NewsService::getAllNews', $e->getMessage());
            return [];
        }
    }
}
