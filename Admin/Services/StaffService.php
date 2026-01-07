<?php

/**
 * StaffService
 * Business logic for Staff (polling staff/users) operations.
 */

class StaffService extends BaseService
{
    public function getTableName(): string
    {
        return 'users';
    }

    /**
     * Get all staff members (UserType = 1).
     * 
     * @param Pagination|null $pagination Pagination object (optional)
     * @return array Array of staff
     */
    public function getAll(?Pagination $pagination = null): array
    {
        $sql = "SELECT * FROM users WHERE UserType = 1 ORDER BY UserID ASC";

        if ($pagination) {
            $sql .= " " . $pagination->getSqlLimit();
        }

        try {
            $stmt = db_query($sql);
            return db_fetch_all($stmt) ?? [];
        } catch (Exception $e) {
            ErrorHandler::log($e, 'StaffService::getAll', false);
            return [];
        }
    }

    /**
     * Get count of staff members (UserType = 1).
     * 
     * @return int Total count
     */
    public function getCount(): int
    {
        $sql = "SELECT COUNT(*) as total FROM users WHERE UserType = 1";

        try {
            $stmt = db_query($sql);
            $result = db_fetch_assoc($stmt);
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            ErrorHandler::log($e, 'StaffService::getCount', false);
            return 0;
        }
    }

    /**
     * Create a new staff member.
     * 
     * @param array $data Staff data (firstName, lastName, birthDate, gender, phoneNumber, userName, password, ...)
     * @return array ['success' => bool, 'id' => int|null, 'error' => string|null]
     */
    public function create(array $data): array
    {
        $required = ['firstName', 'lastName', 'birthDate', 'gender', 'phoneNumber', 'userName', 'password'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return ['success' => false, 'id' => null, 'error' => "Missing required field: $field"];
            }
        }

        $sql = "INSERT INTO users (
            FirstName, LastName, BirthDate, Gender, PhoneNumber, UserName, Password, Image, UnitID, UserType
        ) VALUES (
            :first, :last, :birth, :gender, :phone, :username, :password, :image, :unitid, :usertype
        )";

        try {
            db_query($sql, [
                ':first' => $data['firstName'],
                ':last' => $data['lastName'],
                ':birth' => $data['birthDate'],
                ':gender' => $data['gender'],
                ':phone' => $data['phoneNumber'],
                ':username' => $data['userName'],
                ':password' => $data['password'],
                ':image' => $data['image'] ?? null,
                ':unitid' => $data['unitId'] ?? null,
                ':usertype' => $data['userType'] ?? 2, // Default to staff
            ]);

            $id = db_last_insert_id();
            return ['success' => true, 'id' => $id, 'error' => null];
        } catch (Exception $e) {
            ErrorHandler::log($e, 'StaffService::create', false);
            return ['success' => false, 'id' => null, 'error' => 'Database error'];
        }
    }

    /**
     * Update an existing staff member.
     * 
     * @param int $id User ID
     * @param array $data Data to update
     * @return array ['success' => bool, 'error' => string|null]
     */
    public function update(int $id, array $data): array
    {
        if (!$id) {
            return ['success' => false, 'error' => 'Invalid user ID'];
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

        $sql = "UPDATE users SET " . implode(', ', $updateFields) . " WHERE UserID = :id";

        try {
            db_query($sql, $params);
            return ['success' => true, 'error' => null];
        } catch (Exception $e) {
            ErrorHandler::log($e, 'StaffService::update', false);
            return ['success' => false, 'error' => 'Database error'];
        }
    }

    /**
     * Get staff by username.
     * 
     * @param string $userName Username to search for
     * @return array|null Staff record or null
     */
    public function getByUsername(string $userName): ?array
    {
        $sql = "SELECT * FROM users WHERE UserName = :username LIMIT 1";

        try {
            $stmt = db_query($sql, [':username' => $userName]);
            return db_fetch_assoc($stmt);
        } catch (Exception $e) {
            ErrorHandler::log($e, 'StaffService::getByUsername', false);
            return null;
        }
    }

    /**
     * Get staff by user type.
     * 
     * @param int $userType User type (1=admin, 2=staff)
     * @return array Array of staff
     */
    public function getByType(int $userType): array
    {
        $sql = "SELECT * FROM users WHERE UserType = :type ORDER BY FirstName, LastName";

        try {
            $stmt = db_query($sql, [':type' => $userType]);
            return db_fetch_all($stmt) ?? [];
        } catch (Exception $e) {
            ErrorHandler::log($e, 'StaffService::getByType', false);
            return [];
        }
    }

    /**
     * Get staff by polling unit.
     * 
     * @param int $unitId Polling unit ID
     * @return array Array of staff assigned to the unit
     */
    public function getByUnit(int $unitId): array
    {
        $sql = "SELECT * FROM users WHERE UnitID = :unitid AND UserType = 2 ORDER BY FirstName";

        try {
            $stmt = db_query($sql, [':unitid' => $unitId]);
            return db_fetch_all($stmt) ?? [];
        } catch (Exception $e) {
            ErrorHandler::log($e, 'StaffService::getByUnit', false);
            return [];
        }
    }
}
