<?php

$users = getUsers();
?>

<table class="table table-bordered table-hover users-table">
    <thead>
    <tr>
        <th>
            <label>
                <input class="form-check-input select-all-users-checkbox" type="checkbox">
            </label>
        </th>
        <th scope="col">Name</th>
        <th scope="col">Role</th>
        <th scope="col">Status</th>
        <th scope="col">Options</th>
    </tr>
    </thead>
    <tbody>

    <?php foreach ($users as $user) { ?>
        <?php include 'user-row.php'; ?>
    <?php } ?>

    </tbody>
</table>
