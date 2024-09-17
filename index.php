<?php

require_once 'const.php';
require_once INCLUDES_PATH . '/functions.php';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Management System</title>
    <link rel="stylesheet" href="/assets/css/modules/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>

<div class="container py-5">
    <div class="col-12">

        <h1 class="fs-3 mb-4">Users</h1>

        <div class="d-flex mb-3 gap-3">
            <?php
            include FORM_PATH . '/bulk-actions-form.php'; ?>
        </div>

        <div class="status-messages"></div>

        <?php
        include_once TABLE_PATH . '/users.php'; ?>

        <div class="d-flex mt-3 gap-3">
            <?php
            include FORM_PATH . '/bulk-actions-form.php'; ?>
        </div>

        <?php
        include_once MODAL_PATH . '/create-update-user-modal.php';
        include_once MODAL_PATH . '/delete-user-modal.php';
        include_once MODAL_PATH . '/bulk-actions-modal.php';
        ?>

    </div>
</div>

<script src="/assets/js/modules/jquery-3.7.1.min.js"></script>
<script src="/assets/js/modules/bootstrap.bundle.min.js"></script>
<script src="/assets/js/helpers.js"></script>
<script src="/assets/js/bulk-actions.js"></script>
<script src="/assets/js/user-management.js"></script>
</body>
</html>
