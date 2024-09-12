<?php

function createUser($firstName, $lastName, $roleId, $status): int|string
{
    $query = "INSERT INTO users (first_name, last_name, role_id, status) VALUES (?, ?, ?, ?)";
    preparedQuery($query, [
        $firstName,
        $lastName,
        $roleId,
        $status,
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

function updateUser($id, $firstName, $lastName, $roleId, $status)
{
    $query = "UPDATE users SET first_name = ?, last_name = ?, role_id = ?, status = ? WHERE id = ?";
    preparedQuery($query, [$firstName, $lastName, $roleId, $status, $id],
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

function updateUsersStatusMultiple(array $users_ids, int $status)
{
    $placeholders = getPlaceholders($users_ids);
    $query = "UPDATE users SET status = '$status' WHERE id IN ($placeholders)";
    return preparedQuery($query, $users_ids);
}

function deleteUsersMultiple(array $users_ids)
{
    $placeholders = getPlaceholders($users_ids);
    $query = "DELETE FROM users WHERE id IN ($placeholders)";
    return preparedQuery($query, $users_ids);
}

function isUserExists($id)
{
    $query = "SELECT id FROM users WHERE id = ? LIMIT 1";
    $stmt = preparedQuery($query, [$id]);
    return mysqli_num_rows(getStmtResult($stmt)) === 1;
}
