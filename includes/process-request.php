<?php

require_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'user_create':
            handleUserCreate();
            break;
        case 'user_update':
            handleUserUpdate();
            break;
        case 'user_delete':
            handleUserDelete();
            break;
        case 'user_delete_multiple':
            handleUserDeleteMultiple($_POST['user_id']);
            break;
        case 'bulk_action':
            handleBulkAction();
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        case 'user_get':
            requestUserGet($_GET['user_id']);
            break;
        case 'user_get_multiple':
            requestUserGetMultiple($_GET['users_ids']);
            break;
    }
}
