<?php

function handleBulkAction(string $bulkAction, string $usersIdsStr): void
{
    if (empty($bulkAction) || empty($usersIdsStr)) {
        handleJsonOutput(buildResponseData(false, 100, 'Users not found.'));
    }

    $usersIds = explode(',', $usersIdsStr);

    handleCountNotEqualsResponse($usersIds);

    switch ($bulkAction) {
        case 'set_active':
            updateUsersStatusMultiple($usersIds, 1);
            break;
        case 'set_not_active':
            updateUsersStatusMultiple($usersIds, 0);
            break;
        default:
            invalidActionHandler();
    }

    handleJsonOutput(buildResponseData(true));
}

function handleUserDeleteMultiple($users_ids): void
{
    if (empty($users_ids)) {
        handleJsonOutput(buildResponseData(false, 100, 'No users ids provided.'));
    }

    $usersIds = explode(',', $users_ids);

    handleCountNotEqualsResponse($usersIds);

    deleteUsersMultiple($usersIds);

    handleJsonOutput(buildResponseData(true, extraData: ['users_ids' => $usersIds]));
}

function handleCountNotEqualsResponse(array $ids): void
{
    $usersCount = getColumnRecordsInTableCount('users', 'id', $ids);

    if (count($ids) !== $usersCount) {
        handleJsonOutput(buildResponseData(false, 404, 'Some of the selected users were not found. Please refresh the page and try again.'));
    }
}