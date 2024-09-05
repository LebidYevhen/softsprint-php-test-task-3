<form method="post" class="user-add-form">
    <div class="mb-3">
        <label for="add_first_name" class="form-label">First name</label>
        <input type="text" class="form-control" id="add_first_name" name="first_name">
    </div>
    <div class="mb-3">
        <label for="add_last_name" class="form-label">Last name</label>
        <input type="text" class="form-control" id="add_last_name" name="last_name">
    </div>
    <div class="form-check form-switch ps-0 mb-3">
        <label class="form-check-label" for="add_status">Status</label>
        <input class="form-check-input ms-0 float-none d-block" type="checkbox" role="switch" id="add_status"
               name="status">
    </div>
    <div class="mb-3">
        <label class="form-label" for="add_role_id">Role</label>
        <select class="form-select" aria-label="User add role select" id="add_role_id" name="role_id">
            <option value="" selected>-Please Select-</option>
            <?php
            foreach ($user_roles as $role) {
                echo sprintf('<option value="%s">%s</option>', $role['id'], $role['name']);
            } ?>
        </select>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Add</button>
    </div>
    <input type="hidden" name="action" value="user_add">
</form>