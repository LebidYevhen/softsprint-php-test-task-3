<?php

require_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
    $action = sanitizeInput($_POST['action']);
    $userId = isset($_POST['user_id']) ? sanitizeInput($_POST['user_id']) : null;

    switch ($action) {
        case 'user_create':
            handleUserCreate();
            break;
        case 'user_update':
            $firstName = isset($_POST['first_name']) ? sanitizeInput($_POST['first_name']) : null;
            $lastName = isset($_POST['last_name']) ? sanitizeInput($_POST['last_name']) : null;
            $roleId = isset($_POST['role_id']) ? sanitizeInput($_POST['role_id']) : null;
            $status = isset($_POST['status']) ? sanitizeInput($_POST['status']) : null;

            handleUserUpdate(
                $userId,
                $firstName,
                $lastName,
                $roleId,
                filter_var(sanitizeInput($_POST['status']), FILTER_VALIDATE_BOOLEAN) ? 1 : 0
            );
            break;
        case 'user_delete':
            handleUserDelete($userId);
            break;
        case 'user_delete_multiple':
            handleUserDeleteMultiple($userId);
            break;
        case 'bulk_action':
            $bulkAction = isset($_POST['bulk_action']) ? sanitizeInput($_POST['bulk_action']) : null;
            $usersIds = isset($_POST['users_ids']) ? sanitizeInput($_POST['users_ids']) : null;

            handleBulkAction($bulkAction, $usersIds);
            break;
        default:
            invalidActionHandler();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['action'])) {
    $action = sanitizeInput($_GET['action']);

    switch ($action) {
        case 'user_get':
            $userId = isset($_GET['user_id']) ? sanitizeInput($_GET['user_id']) : null;

            requestUserGet($userId);
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
