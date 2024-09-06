<?php

function validateForm($fields = []): array
{
    $errors = [];
    $validationRules = getValidationRules();

    foreach ($fields as $fieldName => $rules) {
        $fieldValue = $rules['value'];
        foreach ($rules['rules'] as $rule) {
            if (str_contains($rule, ':')) {
                [$ruleName, $ruleParams] = explode(':', $rule, 2);
                $ruleParams = explode(',', $ruleParams);
            } else {
                $ruleName = $rule;
                $ruleParams = [];
            }

            if (!isset($validationRules[$ruleName])) {
                $errors[$fieldName] = "Validation rule '$ruleName' is not defined.";
                break;
            }

            $isValid = $validationRules[$ruleName]['validate']($fieldValue, ...$ruleParams);

            if (!$isValid) {
                $errors[$fieldName] = $validationRules[$ruleName]['message'];
                break;
            }
        }
    }

    return $errors;
}

function getValidationRules(): array
{
    return [
        'required' => [
            'validate' => fn($value) => !empty($value),
            'message' => 'This field is required.'
        ],
        'exists' => [
            'validate' => fn($value, $table, $column) => isValueInTableExists($value, $table, $column),
            'message' => 'The selected value does not exist.'
        ],
    ];
}