<form class="user-delete-form">
    <button type="submit" class="btn btn-danger">Delete</button>
    <input type="hidden" name="action" value="user_delete">
    <input type="hidden" name="user_id" id="user_id" value="<?php echo $_POST['user_id'] ?? ''; ?>">
</form>