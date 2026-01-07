<?php

/**
 * ContestantService
 * Business logic for Contestant operations.
 */

class ContestantService extends BaseService
{
    public function getTableName(): string
    {
        return 'contestant';
    }

    /**
     * Create a new contestant.
     * 
     * @param array $data Contestant data (firstName, otherNames, partyName, position, state, image, ...)
     * @return array ['success' => bool, 'id' => int|null, 'error' => string|null]
     */
    public function create(array $data): array
    {
        // Validate required fields
        $required = ['firstName', 'otherNames', 'partyName', 'position', 'state'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return ['success' => false, 'id' => null, 'error' => "Missing required field: $field"];
            }
        }

        $sql = "INSERT INTO contestant (
            FirstName, OtherNames, PartyName, Position, State, Image, FedConstituency, StateConstituency, SenateZone
        ) VALUES (
            :first, :other, :party, :position, :state, :image, :fed, :stateconst, :senate
        )";

        try {
            db_query($sql, [
                ':first' => $data['firstName'],
                ':other' => $data['otherNames'],
                ':party' => $data['partyName'],
                ':position' => $data['position'],
                ':state' => $data['state'],
                ':image' => $data['image'] ?? null,
                ':fed' => $data['fedConstituency'] ?? null,
                ':stateconst' => $data['stateConstituency'] ?? null,
                ':senate' => $data['senateZone'] ?? null,
            ]);

            $id = db_last_insert_id();
            return ['success' => true, 'id' => $id, 'error' => null];
        } catch (Exception $e) {
            ErrorHandler::log($e, 'ContestantService::create', false);
            return ['success' => false, 'id' => null, 'error' => 'Database error'];
        }
    }

    /**
     * Update an existing contestant.
     * 
     * @param int $id Contestant ID
     * @param array $data Data to update
     * @return array ['success' => bool, 'error' => string|null]
     */
    public function update(int $id, array $data): array
    {
        if (!$id) {
            return ['success' => false, 'error' => 'Invalid contestant ID'];
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

        $sql = "UPDATE contestant SET " . implode(', ', $updateFields) . " WHERE ContestantID = :id";

        try {
            db_query($sql, $params);
            return ['success' => true, 'error' => null];
        } catch (Exception $e) {
            ErrorHandler::log($e, 'ContestantService::update', false);
            return ['success' => false, 'error' => 'Database error'];
        }
    }

    /**
     * Get contestants by position.
     * 
     * @param string $position Position name
     * @return array Array of contestants
     */
    public function getByPosition(string $position): array
    {
        $sql = "SELECT * FROM contestant WHERE Position = :position ORDER BY State, FirstName";

        try {
            $stmt = db_query($sql, [':position' => $position]);
            return db_fetch_all($stmt) ?? [];
        } catch (Exception $e) {
            ErrorHandler::log($e, 'ContestantService::getByPosition', false);
            return [];
        }
    }

    /**
     * Get contestants by state.
     * 
     * @param string $state State name
     * @param string|null $position Optional position filter
     * @return array Array of contestants
     */
    public function getByState(string $state, ?string $position = null): array
    {
        $sql = "SELECT * FROM contestant WHERE State = :state";
        $params = [':state' => $state];

        if ($position) {
            $sql .= " AND Position = :position";
            $params[':position'] = $position;
        }

        $sql .= " ORDER BY FirstName, OtherNames";

        try {
            $stmt = db_query($sql, $params);
            return db_fetch_all($stmt) ?? [];
        } catch (Exception $e) {
            ErrorHandler::log($e, 'ContestantService::getByState', false);
            return [];
        }
    }

    /**
     * Set contestant's result visibility (Public/Private).
     * 
     * @param int $id Contestant ID
     * @param string $resultMode 'Public' or 'Private'
     * @return array ['success' => bool, 'error' => string|null]
     */
    public function setResultMode(int $id, string $resultMode): array
    {
        if (!in_array($resultMode, ['Public', 'Private'], true)) {
            return ['success' => false, 'error' => 'Invalid result mode'];
        }

        return $this->update($id, ['ResultMode' => $resultMode]);
    }
}
