<?php
session_start();

date_default_timezone_set('Europe/Minsk');

require_once $_SERVER[DOCUMENT_ROOT] . '/api/boot.php';

$api = new Api();
?>