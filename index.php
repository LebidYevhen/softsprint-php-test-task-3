<?php

require_once 'const.php';
require_once INCLUDES_PATH . '/functions.php';
require_once INCLUDES_PATH . '/header.php';
?>

    <h1 class="fs-3 mb-4">Users</h1>

    <div class="d-flex mb-3 gap-3">
        <?php
        include 'includes/form/bulk-action.php'; ?>
    </div>

    <div class="status-messages"></div>

<?php
include_once 'includes/table/users.php'; ?>

    <div class="d-flex mt-3 gap-3">
        <?php
        include 'includes/form/bulk-action.php'; ?>
    </div>

<?php
include_once MODAL_PATH . '/create-update-user-modal.php';
include_once MODAL_PATH . '/delete-user-modal.php';
include_once MODAL_PATH . '/bulk-actions-modal.php';
?>

<?php
require_once 'includes/footer.php'; ?>