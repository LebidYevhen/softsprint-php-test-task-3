<?php

require_once '../functions.php';

if (isset($_POST['user_id'])) {
    $user = getUser($_POST['user_id']);
}

?>
<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel"
     aria-hidden="true">
    <div class="modal-inner">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="deleteUserModalLabel">Delete Confirmation</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cancel"></button>
                </div>
                <div class="modal-body">
                    <?php if (empty($user)) { ?>
                        User not found.
                    <?php } else { ?>
                        <?php ?>
                        Are you sure you want to delete <span
                                class="fw-bold modal-user-name"><?php echo "$user[first_name] $user[last_name]"; ?></span>?
                    <?php } ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <?php include_once '../form/user-delete.php'; ?>
                </div>
            </div>
        </div>
    </div>
</div>