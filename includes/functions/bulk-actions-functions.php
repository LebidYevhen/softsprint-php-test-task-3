<?php

function handleBulkAction(string $bulkAction, string $usersIdsStr): void
{
    if (empty($bulkAction) || empty($usersIdsStr)) {
        $data = [
            'status' => false,
            'error' => [
                'code' => 100,
                'message' => 'Users not found.'
            ],
        ];

        handleJsonOutput($data);
    }

    $usersIds = explode(',', $usersIdsStr);

    switch ($_POST['bulk_action']) {
        case 'set_active':
            updateUsersStatusMultiple($usersIds, 1);
            break;
        case 'set_not_active':
            updateUsersStatusMultiple($usersIds, 0);
            break;
    }

    $data = [
        'status' => true,
        'error' => null,
    ];

    handleJsonOutput($data);
}

function handleUserDeleteMultiple($users_ids): void
{
    if (empty($users_ids)) {
        $data = [
            'status' => false,
            'error' => [
                'code' => 100,
                'error' => 'No users ids provided.',
            ],
        ];

        handleJsonOutput($data);
    }

    echo '<pre>';
    print_r($users_ids);
    echo '</pre>';
    die();

    $usersIds = explode(',', $users_ids);

    deleteUsersMultiple($usersIds);
    $data = [
        'status' => true,
        'error' => null,
        'users_ids' => $usersIds,
    ];

    handleJsonOutput($data);
}