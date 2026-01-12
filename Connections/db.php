<?php
// DB helper wrapper around PDO. If Connections/localhost.php is missing, attempt
// to bootstrap a PDO connection from environment variables (works in Docker).
if (!isset($pdo) || !$pdo instanceof PDO) {
    // Try legacy include first
    if (file_exists(__DIR__ . '/localhost.php')) {
        require_once __DIR__ . '/localhost.php';
    }

    // Fallback: build PDO using env vars (Docker-compose provides these)
    if (!isset($pdo) || !$pdo instanceof PDO) {
        $dbHost = getenv('DB_HOST') ?: 'db';
        $dbName = getenv('DB_NAME') ?: 'inec';
        $dbUser = getenv('DB_USER') ?: 'root';
        $dbPass = getenv('DB_PASSWORD') ?: 'root';
        $dbPort = getenv('DB_PORT') ?: '3306';

        $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8";
        try {
            $pdo = new PDO($dsn, $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (Throwable $e) {
            // Leave $pdo unset; callers will throw a clearer error
        }
    }
}

function db()
{
    global $pdo;
    return $pdo;
}

function db_query($sql, $params = [])
{
    $pdo = db();
    if (!$pdo) {
        throw new RuntimeException('PDO connection not available');
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

function db_fetch_assoc($stmt)
{
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function db_fetch_all($stmt)
{
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function db_rowcount($stmt)
{
    return $stmt->rowCount();
}

function db_last_insert_id()
{
    $pdo = db();
    return $pdo->lastInsertId();
}

function db_escape($value)
{
    $pdo = db();
    if (!$pdo) return addslashes($value);
    return substr($pdo->quote($value), 1, -1);
}

// Convenience helper for the previous GetSQLValueString pattern (discouraged).
function GetSQLValueStringPDO($value, $type)
{
    if ($value === null || $value === '') {
        return 'NULL';
    }
    switch ($type) {
        case 'text':
            return "'" . db_escape($value) . "'";
        case 'int':
        case 'long':
            return intval($value);
        case 'double':
            return doubleval($value);
        case 'date':
            return "'" . db_escape($value) . "'";
        default:
            return "'" . db_escape($value) . "'";
    }
}

?>