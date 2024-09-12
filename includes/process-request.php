<?php

require_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
    $action = sanitizeData($_POST['action']);
    $userId = isset($_POST['id']) ? sanitizeData($_POST['id']) : null;

    switch ($action) {
        case 'user_create':
            handleUserCreate();
            break;
        case 'user_update':
            $firstName = isset($_POST['first_name']) ? sanitizeData($_POST['first_name']) : null;
            $lastName = isset($_POST['last_name']) ? sanitizeData($_POST['last_name']) : null;
            $roleId = isset($_POST['role_id']) ? sanitizeData($_POST['role_id']) : null;
            $status = isset($_POST['status']) ? sanitizeData($_POST['status']) : null;

            handleUserUpdate(
                $userId,
                $firstName,
                $lastName,
                $roleId,
                filter_var(sanitizeData($_POST['status']), FILTER_VALIDATE_BOOLEAN) ? 1 : 0
            );
            break;
        case 'user_delete':
            handleUserDelete($userId);
            break;
        case 'user_delete_multiple':
            handleUserDeleteMultiple($userId);
            break;
        case 'bulk_action':
            $bulkAction = isset($_POST['bulk_action']) ? sanitizeData($_POST['bulk_action']) : null;
            $usersIds = isset($_POST['users_ids']) ? sanitizeData($_POST['users_ids']) : null;

            handleBulkAction($bulkAction, $usersIds);
            break;
        default:
            invalidActionHandler();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['action'])) {
    $action = sanitizeData($_GET['action']);

    switch ($action) {
        case 'user_get':
            $userId = isset($_GET['id']) ? sanitizeData($_GET['id']) : null;

            requestUserGet($userId);
            break;
        case 'user_get_count':
            $usersIds = isset($_GET['users_ids']) ? sanitizeData($_GET['users_ids']) : null;

            requestUsersCountGet(explode(',', $usersIds));
            break;
        default:
            invalidActionHandler();
    }
}

function invalidActionHandler()
{
    handleJsonOutput([
        'status' => false,
        'error' => [
            'code' => 400,
            'message' => 'Invalid action.',
        ]
    ]);
}
