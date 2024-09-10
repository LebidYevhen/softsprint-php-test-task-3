<?php

function handleBulkAction(string $bulkAction, string $usersIds): void
{
    if (empty($bulkAction) || empty($usersIds)) {
        $data = [
            'status' => false,
            'error' => [
                'code' => 100,
                'Users not found.'
            ],
        ];

        handleJsonOutput($data);
    }


    $usersIds = explode(',', $usersIds);
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

    $usersIds = explode(',', $users_ids);

    deleteUsersMultiple($usersIds);
    $data = [
        'status' => true,
        'error' => null,
        'users_ids' => $usersIds,
    ];

    handleJsonOutput($data);
}