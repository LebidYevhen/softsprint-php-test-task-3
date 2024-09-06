<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/const.php';
require_once INCLUDES_PATH . 'config.php';
require_once FUNCTIONS_PATH . '/database-functions.php';
$connection = getConnection();
require_once FUNCTIONS_PATH .  '/validation-functions.php';
require_once FUNCTIONS_PATH . '/user-functions.php';
require_once FUNCTIONS_PATH . '/user-request-functions.php';
require_once FUNCTIONS_PATH . '/bulk-actions-functions.php';
require_once FUNCTIONS_PATH . '/helper-functions.php';
