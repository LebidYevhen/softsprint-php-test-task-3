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
        case 'bulk_action':
            handleBulkAction();
            break;
        case 'user_delete_multiple':
            handleUserDeleteMultiple();
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        case 'user_get':
            requestUserGet($_GET['user_id']);
            break;
    }
}
