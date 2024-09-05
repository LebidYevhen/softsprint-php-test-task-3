<?php

function createUser($first_name, $last_name, $role_id, $status): int|string
{
    $query = "INSERT INTO users (first_name, last_name, role_id, status) VALUES (?, ?, ?, ?)";
    preparedQuery($query, [
        sanitizeInput($first_name),
        sanitizeInput($last_name),
        sanitizeInput($role_id),
        filter_var(sanitizeInput($status), FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
    ]);

    return getLastInsertedId();
}

function updateUser($id, $first_name, $last_name, $role_id, $status)
{
    $user = getUser($id);

    $fieldsToUpdate = [];
    $params = [];

    if ($first_name !== $user['first_name']) {
        $fieldsToUpdate['first_name'] = "first_name = ?";
        $params[] = sanitizeInput($first_name);
    }
    if ($last_name !== $user['last_name']) {
        $fieldsToUpdate['last_name'] = "last_name = ?";
        $params[] = sanitizeInput($last_name);
    }
    if ($role_id != $user['role_id']) {
        $fieldsToUpdate['role_id'] = "role_id = ?";
        $params[] = sanitizeInput($role_id);
    }
    $new_status = filter_var(sanitizeInput($status), FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
    if ($new_status != $user['status']) {
        $fieldsToUpdate['status'] = "status = ?";
        $params[] = $new_status;
    }

    if (empty($fieldsToUpdate)) {
        return [
            'id' => $id,
            'updatedFields' => null,
        ];
    }

    $params[] = sanitizeInput($id);

    $query = "UPDATE users SET " . implode(', ', $fieldsToUpdate) . " WHERE id = ?";
    preparedQuery($query, $params);

    return [
        'id' => $id,
        'updatedFields' => array_keys($fieldsToUpdate),
    ];
}

function deleteUser(int $id): false|mysqli_stmt
{
    $query = "DELETE FROM users WHERE id = ? LIMIT 1";
    return preparedQuery($query, [$id]);
}

function usersDeleteMultiple(array $users_ids): false|mysqli_stmt
{
    $placeholders = rtrim(str_repeat('?,', count($users_ids)), ',');
    $query = "DELETE FROM users WHERE id IN ($placeholders)";
    return preparedQuery($query, $users_ids);
}

function usersStatusMultiple(array $users_ids, int $status): false|mysqli_stmt
{
    $placeholders = rtrim(str_repeat('?,', count($users_ids)), ',');
    $query = "UPDATE users SET status = '$status' WHERE id IN ($placeholders)";
    return preparedQuery($query, $users_ids);
}

function getUser(int $id): false|array|null
{
    $query = "SELECT u.id, u.first_name, u.last_name, u.status, ur.id as role_id, ur.name AS role_name
              FROM users as u
              INNER JOIN user_roles AS ur ON u.role_id = ur.id
              WHERE u.id = ? LIMIT 1";
    $stmt = preparedQuery($query, [sanitizeInput($id)]);
    $result = getStmtResult($stmt);
    if (mysqli_num_rows($result) != 1) {
        return null;
    }
    return mysqli_fetch_assoc($result);
}

function getUsers(): array
{
    $query = "SELECT u.id, u.first_name, u.last_name, u.status, ur.id as role_id, ur.name AS role_name
              FROM users AS u
              INNER JOIN user_roles AS ur ON u.role_id = ur.id
              ORDER BY u.id DESC";
    $stmt = preparedQuery($query);
    return mysqli_fetch_all(getStmtResult($stmt), MYSQLI_ASSOC);
}

function getUserRoles(): array
{
    $query = "SELECT id, name FROM user_roles";
    $stmt = preparedQuery($query);

    return mysqli_fetch_all(getStmtResult($stmt), MYSQLI_ASSOC);
}

function handleUserAdd(): void
{
    $validate = validateForm([
        'first_name' => ['required'],
        'last_name' => ['required'],
        'role_id' => ['required'],
    ]);

    if (empty($validate)) {
        $userId = createUser($_POST['first_name'], $_POST['last_name'], $_POST['role_id'], $_POST['status']);
        $data = [
            'status' => true,
            'code' => 200,
            'error' => null,
            'id' => $userId,
        ];
    } else {
        $data = [
            'status' => false,
            'code' => 403,
            'error' => $validate,
        ];
    }

    echo json_encode($data);
}

function handleUserUpdate(): void
{
    $validate = validateForm([
        'first_name' => ['required'],
        'last_name' => ['required'],
        'role_id' => ['required'],
    ]);

    if (empty($validate)) {
        $updateUser = updateUser($_POST['user_id'], $_POST['first_name'], $_POST['last_name'], $_POST['role_id'], $_POST['status']);
        $data = [
            'status' => true,
            'code' => 200,
            'error' => null,
            'id' => $updateUser['id'],
            'updatedFields' => $updateUser['updatedFields'],
        ];
    } elseif (!isUserExists($_POST['user_id'])) {
        $data = [
            'status' => false,
            'code' => 100,
            'error' => 'User not found.',
        ];
    } else {
        $data = [
            'status' => false,
            'code' => 403,
            'error' => $validate,
        ];
    }

    echo json_encode($data);
}

function isUserExists($id)
{
    $query = "SELECT id FROM users WHERE id = ? LIMIT 1";
    $stmt = preparedQuery($query, [$id]);
    return mysqli_num_rows(getStmtResult($stmt)) === 1;
}

function handleUserGet(int $user_id): void
{
    $user = getUser($user_id);

//    if ($user) {
//        $data = [
//            ...$user,
//            'status' => true,
//        ];
//    } else {
//        $data = [
//            'status' => false,
//            'code' => 100,
//            'error' => 'User not found.',
//        ];
//    }

//    echo json_encode($data);

    include TABLE_PATH . '/user-row.php';
}

function handleUserDelete(): void
{
    if (isset($_POST['user_id']) && getUser((int)$_POST['user_id'])) {
        deleteUser($_POST['user_id']);
        $data = [
            'status' => true,
            'code' => 200,
            'error' => null,
        ];
    } else {
        $data = [
            'status' => false,
            'code' => 100,
            'error' => 'User not found.',
        ];
    }

    echo json_encode($data);
}

function handleUserDeleteMultiple(): void
{
    if (isset($_POST['users_ids'])) {
        $usersIds = explode(',', $_POST['users_ids']);
        usersDeleteMultiple($usersIds);
        $data = [
            'status' => true,
            'code' => 200,
            'error' => null,
            'users_ids' => $usersIds,
        ];
    } else {
        $data = [
            'status' => false,
            'code' => 100,
            'error' => 'No users ids provided.',
        ];
    }

    echo json_encode($data);
}

function handleBulkAction(): void
{
    if (isset($_POST['bulk_action'])) {
        $data = [
            'status' => true,
            'code' => 200,
            'error' => null,
        ];

        switch ($_POST['bulk_action']) {
            case 'delete':
                usersDeleteMultiple($_POST['users_ids']);
                echo json_encode($data);
                break;
            case 'set_active':
                usersStatusMultiple($_POST['users_ids'], 1);
                echo json_encode($data);
                break;
            case 'set_not_active':
                usersStatusMultiple($_POST['users_ids'], 0);
                echo json_encode($data);
                break;
        }
    }
}