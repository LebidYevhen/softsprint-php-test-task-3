<?php

$userRoles = getRoles();
?>

<!-- Add User Modal -->
<div class="modal fade" id="userCreateUpdateModal" tabindex="-1" aria-labelledby="userCreateUpdateModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" class="user-create-update-form">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="userCreateUpdateModalLabel"></h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name">
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name">
                    </div>
                    <div class="form-check form-switch ps-0 mb-3">
                        <label class="form-check-label" for="status">Status</label>
                        <input class="form-check-input ms-0 float-none d-block" type="checkbox" role="switch"
                               id="status"
                               name="status">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="role_id">Role</label>
                        <select class="form-select" aria-label="User add role select" id="role_id" name="role_id">
                            <option value="" selected>-Please Select-</option>
                            <?php
                            foreach ($userRoles as $id => $name) {
                                echo sprintf('<option value="%s" data-role-name="%s">%s</option>', $id, $name, $name);
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
                <input type="hidden" name="action">
            </form>
        </div>
    </div>
</div>