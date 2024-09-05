<?php

function validateForm($fields = []): array
{
    $errors = [];
    $validationRules = getValidationRules();

    foreach ($fields as $fieldName => $rules) {
        $fieldValue = $_POST[$fieldName] ?? null;
        foreach ($rules as $rule) {
            !$validationRules[$rule]['validate']($fieldValue) ?: $errors[$fieldName] = $validationRules[$rule]['message'];
        }
    }

    return $errors;
}

function getValidationRules(): array
{
    return [
        'required' => [
            'validate' => fn($fieldValue) => empty($fieldValue),
            'message' => 'This field is required.'
        ],
    ];
}