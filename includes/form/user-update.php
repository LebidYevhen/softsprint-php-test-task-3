<form class="user-update-form">
    <div class="mb-3">
        <label for="update_first_name" class="form-label">First name</label>
        <input type="text" class="form-control" id="update_first_name" name="first_name"
               value="<?php echo $user['first_name']; ?>">
    </div>
    <div class="mb-3">
        <label for="update_last_name" class="form-label">Last name</label>
        <input type="text" class="form-control" id="update_last_name" name="last_name"
               value="<?php echo $user['last_name']; ?>">
    </div>
    <div class="form-check form-switch ps-0 mb-3">
        <label class="form-check-label" for="update_status">Status</label>
        <input class="form-check-input ms-0 float-none d-block" type="checkbox" role="switch" id="update_status"
               name="status" <?php echo $user['status'] ? 'checked' : ''; ?>>
    </div>
    <div class="mb-3">
        <label class="form-label" for="update_role">Role</label>
        <select class="form-select" aria-label="User update role select" id="update_role" name="role_id">
            <option>-Please Select-</option>
            <?php foreach (getUserRoles() as $role) { ?>
                <option value="<?php echo $role['id']; ?>" <?php echo $user['role_id'] === $role['id'] ? 'selected' : ''; ?>><?php echo $role['name']; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
    <input type="hidden" name="action" value="user_update">
    <input type="hidden" name="user_id" id="user_id" value="<?php echo $_POST['user_id'] ?? ''; ?>">
</form>