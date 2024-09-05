<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">Add</button>
<form class="d-flex gap-3 bulk-action-form" method="post">
    <select class="form-select w-auto bulk-action-select" aria-label="Actions select" name="bulk_action">
        <option value="" selected>-Please-Select-</option>
        <option value="set_active">Set active</option>
        <option value="set_not_active">Set not active</option>
        <option value="delete">Delete</option>
    </select>
    <input class="btn btn-primary bulk-action-submit" type="submit" value="OK">
    <input type="hidden" name="action" value="bulk_action">
</form>