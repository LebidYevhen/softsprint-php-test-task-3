<?php

function sanitizeData(mixed $data): array|string
{
    if (!is_array($data)) {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    } else {
        return array_map('sanitizeData', $data);
    }
}
