<?php

$users = getUsers();
$userRoles = getRoles();
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
        <tr data-user-id="<?php echo $user['id']; ?>" class="user-table-row">
            <td class="align-middle">
                <label class="form-check-label">
                    <input class="form-check-input user-id user-selection-checkbox" type="checkbox"
                           name="checkBoxesArray[]"
                           value="<?php echo $user['id']; ?>">
                </label>
            </td>
            <td class="align-middle user-fullname"><?php echo "$user[first_name] $user[last_name]"; ?></td>
            <td class="align-middle user-role"><?php echo $userRoles[$user['role_id']]; ?></td>
            <td class="text-center align-middle user-status <?php echo $user['status'] ? 'active' : ''; ?>">
                <span class="user-status-indicator"></span>
            </td>
            <td class="text-center align-middle">
                <div class="border border-dark rounded d-inline-block">
                    <a href="#" class="d-inline-block text-decoration-none p-1 border-end border-dark user-update-link"
                       data-user-create-update-action="user_update">
                        <i class="bi bi-pencil-square text-secondary"></i>
                    </a>
                    <a href="#"
                       class="d-inline-block text-decoration-none p-1 user-delete-link">
                        <i class="bi bi-trash-fill text-secondary"></i>
                    </a>
                </div>
            </td>
        </tr>
    <?php } ?>

    </tbody>
</table>
