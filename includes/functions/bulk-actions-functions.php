<?php

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

function usersDeleteMultiple(array $users_ids)
{
    $placeholders = rtrim(str_repeat('?,', count($users_ids)), ',');
    $query = "DELETE FROM users WHERE id IN ($placeholders)";
    return preparedQuery($query, $users_ids);
}

function usersStatusMultiple(array $users_ids, int $status)
{
    $placeholders = rtrim(str_repeat('?,', count($users_ids)), ',');
    $query = "UPDATE users SET status = '$status' WHERE id IN ($placeholders)";
    return preparedQuery($query, $users_ids);
}