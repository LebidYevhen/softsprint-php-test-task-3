<?php

function getConnection()
{
    try {
        return mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    } catch (Exception $e) {
        die("Connection failed: ".$e->getMessage());
    }
}

function preparedQuery(string $query, array $params = [], string|null $types = null): false|mysqli_stmt
{
    global $connection;

    $stmt = mysqli_prepare($connection, $query);
    if (!empty($params)) {
        $types = $types ?? str_repeat("s", count($params));
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    if (!mysqli_stmt_execute($stmt)) {
        die('Query Failed: ' . mysqli_error($connection));
    }

    return $stmt;
}

function getStmtResult(mysqli_stmt $stmt): false|mysqli_result
{
    return mysqli_stmt_get_result($stmt);
}

function getLastInsertedId(): int|string
{
    global $connection;

    return mysqli_insert_id($connection);
}

function isValueInTableExists($value, $table, $column, $type = null): bool
{
    $query = "SELECT $column FROM $table WHERE $column = ? LIMIT 1";
    $stmt = getStmtResult(preparedQuery($query, [$value], $type));
    return mysqli_num_rows($stmt) > 0;
}