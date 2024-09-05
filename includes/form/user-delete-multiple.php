<form class="user-delete-multiple-form">
    <button type="submit" class="btn btn-danger">Delete</button>
    <input type="hidden" name="action" value="user_delete_multiple">
    <input type="hidden" name="users_ids" id="users_ids" value="<?php echo implode(',', $_POST['users_ids']) ?? ''; ?>">
</form>