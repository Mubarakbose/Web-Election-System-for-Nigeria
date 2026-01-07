<?php

/**
 * StaffContext
 * Centralized helper to resolve the current polling staff record from session.
 */
class StaffContext
{
    /**
     * Resolve current staff from session data.
     * Looks for MM_Staff (username or id) first, then UserID.
     * Returns an array with safe default keys to avoid undefined index notices.
     */
    public static function current(): array
    {
        $defaults = [
            'UserID' => '',
            'FirstName' => '',
            'LastName' => '',
            'UnitID' => '',
            'Image' => '',
            'BirthDate' => '',
            'Gender' => '',
            'PhoneNumber' => '',
            'UserName' => '',
            'Email' => '',
        ];

        $sessionValue = $_SESSION['MM_Staff'] ?? ($_SESSION['UserID'] ?? null);
        if (!$sessionValue) {
            return $defaults;
        }

        try {
            // Prefer numeric lookup if we received an id; otherwise use username
            if (is_numeric($sessionValue)) {
                $stmt = db_query("SELECT * FROM users WHERE UserID = :id", [':id' => $sessionValue]);
            } else {
                $stmt = db_query("SELECT * FROM users WHERE UserName = :username", [':username' => $sessionValue]);
            }
            $row = db_fetch_assoc($stmt) ?: [];
        } catch (Exception $e) {
            // Log quietly; return safe defaults
            if (class_exists('ErrorHandler')) {
                ErrorHandler::log($e, 'StaffContext::current');
            }
            $row = [];
        }

        return $row + $defaults;
    }
}
