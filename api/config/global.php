<?php
echo 'GLOBAL';
define('BASE_PATH', $_SERVER[DOCUMENT_ROOT]);
define('API_PATH', BASE_PATH . '/api');
define('MODULES_PATH', API_PATH . '/modules');
define('CONFIG_PATH', API_PATH . '/config');
define('CLASSES_PATH', API_PATH . '/classes');
?>