<?php

function requestUserGet($userId): void
{
    $user = getUser($userId);

    if (!$user) {
        handleJsonOutput(buildResponseData(false, 100, 'User not found.'));
    }

    $data = buildResponseData(true, extraData: ['user' => $user]);

    handleJsonOutput($data);
}

function handleUserCreate(): void
{
    $validate = getUserFormValidationResult();

    if (!empty($validate)) {
        $data = buildResponseData(false, 400, 'Validation Error.', errorData: ['fields' => $validate]);

        handleJsonOutput($data);
    }

    $userId = createUser(
        sanitizeData($_POST['first_name']),
        sanitizeData($_POST['last_name']),
        sanitizeData($_POST['role_id']),
        filter_var(sanitizeData($_POST['status']), FILTER_VALIDATE_BOOLEAN) ? 1 : 0
    );

    $data = buildResponseData(true, extraData: ['user' => getUser($userId)]);

    handleJsonOutput($data);
}

function handleUserUpdate($userId, $firstName, $lastName, $roleId, $status): void
{
    $validate = getUserFormValidationResult();

    if (empty($userId) || !isUserExists($userId)) {
        handleJsonOutput(buildResponseData(false, 100, 'User not found.'));
    }

    if (!empty($validate)) {
        handleJsonOutput(buildResponseData(false, 400, 'Validation Error.', errorData: ['fields' => $validate]));
    }

    $userId = updateUser($userId, $firstName, $lastName, $roleId, $status);

    handleJsonOutput(buildResponseData(true, extraData: ['user' => getUser($userId)]));
}

function handleUserDelete($userId): void
{
    if (empty($userId) || !isUserExists($userId)) {
        handleJsonOutput(buildResponseData(false, 100, 'User not found.'));
    }

    $user = getUser($userId);

    deleteUser($userId);

    handleJsonOutput(buildResponseData(true, extraData: ['user' => $user]));
}

function getUserFormValidationResult(): array
{
    return validateForm([
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
}

function buildResponseData($status = false, $code = null, $message = null, array $errorData = [], array $extraData = []): array
{
    $data = [
        'status' => $status,
        'error' => null,
    ];

    if (!$status) {
        $data['error'] = array_merge([
            'code' => $code,
            'message' => $message,
        ], $errorData);
    }

    return array_merge($data, $extraData);
}

function handleJsonOutput(array $data): void
{
    echo json_encode($data);
    die;
}
