<?php

function requestUserGet(int $user_id)
{
    $user = getUser($user_id);

    if ($user) {
        $role = getRoleById($user['role_id']);
        $user['role_name'] = $role['name'];
        $data = [
            'status' => true,
            'error' => null,
            'user' => $user,
        ];
    } else {
        $data = [
            'status' => false,
            'error' => [
                'code' => 100,
                'message' => 'User not found.',
            ]
        ];
    }

    echo json_encode($data);
    die();
}

function requestUserGetMultiple($users_ids)
{
    $users = getUsersByIds($users_ids);
    if (empty($users_ids) || empty($users)) {
        $data = [
            'status' => false,
            'error' => [
                'code' => 100,
                'message' => 'Users not found.',
            ]
        ];
    } else {
        foreach ($users as &$user) {
            $role = getRoleById($user['role_id']);
            $user['role_name'] = $role['name'];
        }

        $data = [
            'status' => true,
            'error' => null,
            'users' => $users,
        ];
    }

    echo json_encode($data);
    die();
}

function handleUserCreate()
{
    $validate = validateForm([
        'first_name' => [
            'rules' => [
                'required',
            ],
            'value' => $_POST['first_name'] ?? null,
        ],
        'last_name' => [
            'rules' => [
                'required',
            ],
            'value' => $_POST['last_name'] ?? null
        ],
        'role_id' => [
            'rules' => [
                'required',
                'exists:user_roles,id'
            ],
            'value' => $_POST['role_id'] ?? null
        ],
    ]);


    if (empty($validate)) {
        $userId = createUser($_POST['first_name'], $_POST['last_name'], $_POST['role_id'], $_POST['status']);
        $user = getUser($userId);
        $role = getRoleById($user['role_id']);
        $user['role_name'] = $role['name'];
        $data = [
            'status' => true,
            'error' => null,
            'success' => [
                'code' => 200,
                'message' => "User $user[first_name] $user[last_name] created successfully.",
            ],
            'user' => $user,
        ];
    } else {
        $data = [
            'status' => false,
            'error' => [
                'code' => 400,
                'message' => 'Validation Error.',
                'fields' => $validate,
            ],
        ];
    }

    echo json_encode($data);
    die;
}

function handleUserUpdate(): void
{
    $validate = validateForm([
        'first_name' => [
            'rules' => [
                'required',
            ],
            'value' => $_POST['first_name'] ?? null,
        ],
        'last_name' => [
            'rules' => [
                'required',
            ],
            'value' => $_POST['last_name'] ?? null
        ],
        'role_id' => [
            'rules' => [
                'required',
                'exists:user_roles,id'
            ],
            'value' => $_POST['role_id'] ?? null
        ],
    ]);

    if (!isset($_POST['user_id']) || !isUserExists($_POST['user_id'])) {
        $data = [
            'status' => false,
            'error' => [
                'code' => 100,
                'message' => 'User not found.',
            ],
        ];
    } elseif (empty($validate)) {
        $userId = updateUser($_POST['user_id']);
        $user = getUser($userId);
        $role = getRoleById($user['role_id']);
        $user['role_name'] = $role['name'];
        $data = [
            'status' => true,
            'error' => null,
            'success' => [
                'code' => 200,
                'message' => "User $user[first_name] $user[last_name] updated successfully.",
            ],
            'user' => $user,
        ];
    } else {
        $data = [
            'status' => false,
            'error' => [
                'code' => 400,
                'message' => 'Validation Error.',
                'fields' => $validate,
            ],
        ];
    }

    echo json_encode($data);
    die;
}

function handleUserDelete(): void
{
    if (!isset($_POST['user_id']) || !isUserExists($_POST['user_id'])) {
        $data = [
            'status' => false,
            'error' => [
                'code' => 100,
                'message' => 'User not found.',
            ],
        ];
    } else {
        $user = getUser($_POST['user_id']);
        deleteUser($_POST['user_id']);
        $data = [
            'status' => true,
            'code' => 200,
            'error' => null,
            'success' => [
                'code' => 200,
                'message' => "User $user[first_name] $user[last_name] deleted successfully.",
            ],
            'user' => $user,
        ];
    }

    echo json_encode($data);
    die;
}