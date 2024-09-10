<?php

function requestUserGet($userId)
{
    $user = getUser($userId);

    if ($user) {
        $data = [
            'status' => true,
            'error' => null,
            'user' => $user,
        ];

        handleJsonOutput($data);
    }

    $data = [
        'status' => false,
        'error' => [
            'code' => 100,
            'message' => 'User not found.',
        ]
    ];

    handleJsonOutput($data);
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

    if (!empty($validate)) {
        $data = [
            'status' => false,
            'error' => [
                'code' => 400,
                'message' => 'Validation Error.',
                'fields' => $validate,
            ],
        ];

        handleJsonOutput($data);
    }

    $userId = createUser(
        sanitizeInput($_POST['first_name']),
        sanitizeInput($_POST['last_name']),
        sanitizeInput($_POST['role_id']),
        filter_var(sanitizeInput($_POST['status']), FILTER_VALIDATE_BOOLEAN) ? 1 : 0
    );
    $data = [
        'status' => true,
        'error' => null,
        'user' => getUser($userId),
    ];

    handleJsonOutput($data);
}

function handleUserUpdate($userId, $firstName, $lastName, $roleId, $status): void
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

    if (!isset($_POST['user_id'])) {
        $data = [
            'status' => false,
            'error' => [
                'code' => 100,
                'message' => 'User not found.',
            ],
        ];

        handleJsonOutput($data);
    }


    if (empty($validate)) {
        $userId = updateUser($userId, $firstName, $lastName, $roleId, $status);
        $data = [
            'status' => true,
            'error' => null,
            'user' => getUser($userId),
        ];

        handleJsonOutput($data);
    }

    $data = [
        'status' => false,
        'error' => [
            'code' => 400,
            'message' => 'Validation Error.',
            'fields' => $validate,
        ],
    ];

    handleJsonOutput($data);
}

function handleUserDelete($userId): void
{
    if (empty($userId) || !isUserExists($userId)) {
        $data = [
            'status' => false,
            'error' => [
                'code' => 100,
                'message' => 'User not found.',
            ],
        ];

        handleJsonOutput($data);
    }
    $data = [
        'status' => true,
        'code' => 200,
        'error' => null,
        'user' => getUser($userId),
    ];

    deleteUser($userId);

    handleJsonOutput($data);
}

function handleJsonOutput(array $data): void
{
    echo json_encode($data);
    die;
}