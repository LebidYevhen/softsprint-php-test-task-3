<?php

function createUser($first_name, $last_name, $role_id, $status): int|string
{
    $query = "INSERT INTO users (first_name, last_name, role_id, status) VALUES (?, ?, ?, ?)";
    preparedQuery($query, [
        sanitizeInput($first_name),
        sanitizeInput($last_name),
        sanitizeInput($role_id),
        filter_var(sanitizeInput($status), FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
    ],
        'ssdd');

    return getLastInsertedId();
}

function getUser($id)
{
    $query = "SELECT id, first_name, last_name, role_id, status
              FROM users WHERE id = ? LIMIT 1";
    $stmt = preparedQuery($query, [$id]);
    return mysqli_fetch_assoc(getStmtResult($stmt));
}

function updateUser($id)
{
    $query = "UPDATE users SET first_name = ?, last_name = ?, role_id = ?, status = ? WHERE id = ?";
    preparedQuery($query, [
        sanitizeInput($_POST['first_name']),
        sanitizeInput($_POST['last_name']),
        sanitizeInput($_POST['role_id']),
        filter_var(sanitizeInput($_POST['status']), FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
        $id
    ],
        'ssddd');

    return $id;
}

function deleteUser($id)
{
    $query = "DELETE FROM users WHERE id = ? LIMIT 1";
    return preparedQuery($query, [$id]);
}

function getUsers(): array
{
    $query = "SELECT id, first_name, last_name, role_id, status
              FROM users
              ORDER BY created_at ASC";
    return mysqli_fetch_all(getStmtResult(preparedQuery($query)), MYSQLI_ASSOC);
}

function getUsersByIds(array $ids): array
{
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $query = "SELECT id, first_name, last_name, role_id, status
              FROM users
              WHERE id IN ($placeholders)
              ORDER BY created_at ASC";
    return mysqli_fetch_all(getStmtResult(preparedQuery($query, $ids)), MYSQLI_ASSOC);
}

function updateUsersStatusMultiple(array $users_ids, int $status)
{
    $placeholders = rtrim(str_repeat('?,', count($users_ids)), ',');
    $query = "UPDATE users SET status = '$status' WHERE id IN ($placeholders)";
    return preparedQuery($query, $users_ids);
}

function deleteUsersMultiple(array $users_ids)
{
    $placeholders = rtrim(str_repeat('?,', count($users_ids)), ',');
    $query = "DELETE FROM users WHERE id IN ($placeholders)";
    return preparedQuery($query, $users_ids);
}

function isUserExists($id)
{
    $query = "SELECT id FROM users WHERE id = ? LIMIT 1";
    $stmt = preparedQuery($query, [$id]);
    return mysqli_num_rows(getStmtResult($stmt)) === 1;
}
