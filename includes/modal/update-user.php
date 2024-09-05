<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/const.php';
require_once INCLUDES_PATH . '/functions.php';

if (isset($_POST['user_id'])) {
    $user = getUser($_POST['user_id']);
}

?>

<!-- Update User Modal -->
<div class="modal fade" id="updateUserModal" tabindex="-1" aria-labelledby="updateUserModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="updateUserModalLabel">Update User</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php include_once '../form/user-update.php'; ?>
            </div>
        </div>
    </div>
</div>