<?php

function getRoleById($id)
{
    $query = "SELECT id, name FROM user_roles WHERE id = ? LIMIT 1";
    return mysqli_fetch_assoc(getStmtResult(preparedQuery($query, [$id])));
}


/**
 * @return array
 *   Returns an array of roles where the key is the role ID and the value is the role name.
 */
function getRoles(): array
{
    $roles = [];

    $query = "SELECT id, name FROM user_roles";
    $stmt = preparedQuery($query);
    $result = getStmtResult($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $roles[$row['id']] = $row['name'];
    }

    return $roles;
}