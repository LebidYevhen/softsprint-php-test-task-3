<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/const.php';
require_once INCLUDES_PATH . '/functions.php';

$modalBody = 'Something went wrong.';

if (isset($_POST['users_ids'])) {
    $modalBody = sprintf('Are you sure you want to delete the users with ids:<span class="fw-bold modal-user-name"> %s</span>?', implode(', ', $_POST['users_ids']));
}

?>
<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserMultipleModal" tabindex="-1" aria-labelledby="deleteUserMultipleModalLabel"
     aria-hidden="true">
    <div class="modal-inner">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="deleteUserMultipleModalLabel">Delete Confirmation</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cancel"></button>
                </div>
                <div class="modal-body">
                    <?php echo $modalBody; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <?php include_once '../form/user-delete-multiple.php'; ?>
                </div>
            </div>
        </div>
    </div>
</div>