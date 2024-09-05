<?php

if (file_exists(INCLUDES_PATH . '/config.local.php')) {
    include INCLUDES_PATH . '/config.local.php';
} else {
    define('DB_HOST', '');
    define('DB_USER', '');
    define('DB_PASS', '');
    define('DB_NAME', '');
}