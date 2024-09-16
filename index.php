<?php

require_once 'const.php';
require_once INCLUDES_PATH . '/functions.php';
require_once INCLUDES_PATH . '/header.php';
?>

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

        <?php
        require_once INCLUDES_PATH . '/footer.php'; ?>

    </div>
</div>
