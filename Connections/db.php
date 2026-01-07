<?php
// DB helper wrapper around PDO. Relies on `$pdo` being created in Connections/localhost.php
if (!isset($pdo) || !$pdo instanceof PDO) {
    // Try to create $pdo if Connections/localhost.php wasn't loaded or failed
    if (file_exists(__DIR__ . '/localhost.php')) {
        require_once __DIR__ . '/localhost.php';
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