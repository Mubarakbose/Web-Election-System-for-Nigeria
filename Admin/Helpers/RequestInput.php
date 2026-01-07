<?php

/**
 * RequestInput Helper
 * Provides safe access to $_POST, $_GET, $_FILES with type coercion and defaults.
 * Centralizes input validation and sanitization.
 */

class RequestInput
{
    /**
     * Get a POST value with optional type coercion and default.
     * 
     * @param string $key The POST key
     * @param string $type Type: 'string', 'int', 'float', 'bool', 'email', 'trim'
     * @param mixed $default Default value if key not found
     * @return mixed The value, coerced to the specified type
     */
    public static function post(string $key, string $type = 'string', mixed $default = null): mixed
    {
        if (!isset($_POST[$key])) {
            return $default;
        }
        return self::coerce($_POST[$key], $type);
    }

    /**
     * Get a GET value with optional type coercion and default.
     */
    public static function get(string $key, string $type = 'string', mixed $default = null): mixed
    {
        if (!isset($_GET[$key])) {
            return $default;
        }
        return self::coerce($_GET[$key], $type);
    }

    /**
     * Get a FILE array (typically $_FILES[$key]).
     */
    public static function file(string $key): ?array
    {
        return $_FILES[$key] ?? null;
    }

    /**
     * Get a SESSION value with optional type coercion and default.
     */
    public static function session(string $key, string $type = 'string', mixed $default = null): mixed
    {
        if (!isset($_SESSION[$key])) {
            return $default;
        }
        return self::coerce($_SESSION[$key], $type);
    }

    /**
     * Coerce a value to the specified type.
     * 
     * @param mixed $value The value to coerce
     * @param string $type Type: 'string', 'int', 'float', 'bool', 'email', 'trim'
     * @return mixed The coerced value
     */
    private static function coerce(mixed $value, string $type): mixed
    {
        return match ($type) {
            'int' => intval($value),
            'float' => floatval($value),
            'bool' => (bool)$value,
            'email' => filter_var($value, FILTER_VALIDATE_EMAIL) ?: null,
            'trim' => is_string($value) ? trim($value) : $value,
            'string' => (string)$value,
            default => $value,
        };
    }

    /**
     * Check if a POST key exists and is not empty.
     */
    public static function hasPost(string $key): bool
    {
        return isset($_POST[$key]) && $_POST[$key] !== '';
    }

    /**
     * Check if a GET key exists and is not empty.
     */
    public static function hasGet(string $key): bool
    {
        return isset($_GET[$key]) && $_GET[$key] !== '';
    }

    /**
     * Check if a FILE was uploaded.
     */
    public static function hasFile(string $key): bool
    {
        return isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK;
    }

    /**
     * Get all POST values as an associative array with optional type mapping.
     * 
     * @param array $typeMap Map of key => type (e.g., ['id' => 'int', 'name' => 'trim'])
     * @return array Coerced POST data
     */
    public static function allPost(array $typeMap = []): array
    {
        $result = [];
        foreach ($_POST as $key => $value) {
            $type = $typeMap[$key] ?? 'trim';
            $result[$key] = self::coerce($value, $type);
        }
        return $result;
    }

    /**
     * Validate that all required keys exist in $_POST.
     * 
     * @param array $requiredKeys List of keys that must be present
     * @return array Array of missing keys, empty if all present
     */
    public static function validateRequired(array $requiredKeys): array
    {
        $missing = [];
        foreach ($requiredKeys as $key) {
            if (!isset($_POST[$key]) || $_POST[$key] === '') {
                $missing[] = $key;
            }
        }
        return $missing;
    }
}
