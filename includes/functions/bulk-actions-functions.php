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

    handleCountNotEqualsResponse($usersIds);

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

    handleCountNotEqualsResponse($usersIds);

    deleteUsersMultiple($usersIds);
    $data = [
        'status' => true,
        'error' => null,
        'users_ids' => $usersIds,
    ];

    handleJsonOutput($data);
}

function handleCountNotEqualsResponse(array $ids)
{
    $usersCount = getColumnRecordsInTableCount('users', 'id', $ids);

    if (count($ids) !== $usersCount) {
        $data = [
            'status' => false,
            'error' => [
                'code' => 404,
                'message' => 'Some of the selected users were not found. Please refresh the page and try again.'
            ],
        ];

        handleJsonOutput($data);
    }
}