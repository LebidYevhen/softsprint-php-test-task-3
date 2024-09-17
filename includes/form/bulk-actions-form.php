<button type="button" class="btn btn-primary user-create-update">Add</button>
<form class="d-flex gap-3 bulk-actions-form" method="post">
    <select class="form-select w-auto bulk-actions-select" aria-label="Actions select" name="bulk_action">
        <option value="" selected>-Please-Select-</option>
        <option value="set_active">Set active</option>
        <option value="set_not_active">Set not active</option>
        <option value="delete">Delete</option>
    </select>
    <input class="btn btn-primary bulk-actions-submit" type="submit" value="OK">
    <input type="hidden" name="action" value="bulk_action">
    <input type="hidden" name="users_ids" value="">
</form>