<?php

/**
 * PollingUnitService
 * Business logic for Polling Unit operations.
 */

class PollingUnitService extends BaseService
{
    public function getTableName(): string
    {
        return 'pollingunit';
    }

    /**
     * Create a new polling unit.
     * 
     * @param array $data Polling unit data (state, lga, puName, ...)
     * @return array ['success' => bool, 'id' => int|null, 'error' => string|null]
     */
    public function create(array $data): array
    {
        $required = ['state', 'lga', 'puName'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return ['success' => false, 'id' => null, 'error' => "Missing required field: $field"];
            }
        }

        $sql = "INSERT INTO pollingunit (State, LGA, PUName) VALUES (:state, :lga, :puname)";

        try {
            db_query($sql, [
                ':state' => $data['state'],
                ':lga' => $data['lga'],
                ':puname' => $data['puName'],
            ]);

            $id = db_last_insert_id();
            return ['success' => true, 'id' => $id, 'error' => null];
        } catch (Exception $e) {
            ErrorHandler::log($e, 'PollingUnitService::create', false);
            return ['success' => false, 'id' => null, 'error' => 'Database error'];
        }
    }

    /**
     * Update an existing polling unit.
     * 
     * @param int $id Polling unit ID
     * @param array $data Data to update
     * @return array ['success' => bool, 'error' => string|null]
     */
    public function update(int $id, array $data): array
    {
        if (!$id) {
            return ['success' => false, 'error' => 'Invalid polling unit ID'];
        }

        $updateFields = [];
        $params = [':id' => $id];

        foreach ($data as $key => $value) {
            if ($value !== null) {
                $updateFields[] = "$key = :$key";
                $params[":$key"] = $value;
            }
        }

        if (empty($updateFields)) {
            return ['success' => false, 'error' => 'No fields to update'];
        }

        $sql = "UPDATE pollingunit SET " . implode(', ', $updateFields) . " WHERE UnitID = :id";

        try {
            db_query($sql, $params);
            return ['success' => true, 'error' => null];
        } catch (Exception $e) {
            ErrorHandler::log($e, 'PollingUnitService::update', false);
            return ['success' => false, 'error' => 'Database error'];
        }
    }

    /**
     * Get polling units by state.
     * 
     * @param string $state State name
     * @return array Array of polling units
     */
    public function getByState(string $state): array
    {
        $sql = "SELECT * FROM pollingunit WHERE State = :state ORDER BY LGA, PUName";

        try {
            $stmt = db_query($sql, [':state' => $state]);
            return db_fetch_all($stmt) ?? [];
        } catch (Exception $e) {
            ErrorHandler::log($e, 'PollingUnitService::getByState', false);
            return [];
        }
    }

    /**
     * Get distinct states.
     * 
     * @return array Array of state names
     */
    public function getDistinctStates(): array
    {
        $sql = "SELECT DISTINCT State FROM pollingunit ORDER BY State";

        try {
            $stmt = db_query($sql);
            $results = db_fetch_all($stmt) ?? [];
            return array_column($results, 'State');
        } catch (Exception $e) {
            ErrorHandler::log($e, 'PollingUnitService::getDistinctStates', false);
            return [];
        }
    }
}
