<?php

function getRoleById($id)
{
    $query = "SELECT id, name FROM user_roles WHERE id = ? LIMIT 1";
    return mysqli_fetch_assoc(getStmtResult(preparedQuery($query, [$id])));
}

function getRoles(): array
{
    $query = "SELECT id, name FROM user_roles";
    return mysqli_fetch_all(getStmtResult(preparedQuery($query)), MYSQLI_ASSOC);
}