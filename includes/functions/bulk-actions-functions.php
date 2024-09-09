<?php

function handleBulkAction(): void
{
    if (isset($_POST['bulk_action']) && !empty($_POST['users_ids'])) {
        $usersIds = explode(',', $_POST['users_ids']);

        switch ($_POST['bulk_action']) {
            case 'set_active':
                updateUsersStatusMultiple($usersIds, 1);
                break;
            case 'set_not_active':
                updateUsersStatusMultiple($usersIds, 0);
                break;
        }

        $users = getUsersByIds($usersIds);
        $usersFullNames = array_map(function ($user) {
            return $user['first_name'] . ' ' . $user['last_name'];
        }, $users);
        foreach ($users as &$user) {
            $role = getRoleById($user['role_id']);
            $user['role_name'] = $role['name'];
        }
        $data = [
            'status' => true,
            'error' => null,
            'users' => $users,
            'success' => [
                'code' => 200,
                'message' => 'Users roles ' . implode(', ', $usersFullNames) . ' changed successfully.',
            ]

        ];

        echo json_encode($data);
        die;
    }
}

function handleUserDeleteMultiple($user_id): void
{
    if (!empty($user_id)) {
        $usersIds = explode(',', $user_id);
        $users = getUsersByIds($usersIds);
        $usersFullNames = array_map(function ($user) {
            return $user['first_name'] . ' ' . $user['last_name'];
        }, $users);

        deleteUsersMultiple($usersIds);
        $data = [
            'status' => true,
            'error' => null,
            'users' => $users,
            'success' => [
                'code' => 200,
                'message' => 'Users ' . implode(', ', $usersFullNames) . ' deleted successfully.',
            ]
        ];
    } else {
        $data = [
            'status' => false,
            'error' => [
                'code' => 100,
                'error' => 'No users ids provided.',
            ],
        ];
    }

    echo json_encode($data);
}