<?php
$start_memory = memory_get_usage();
$start_time = microtime();
require_once $_SERVER[DOCUMENT_ROOT] . '/api/boot.php';

$api = new Api();
$finish_time=microtime();
$result_time = $finish_time - $start_time;
$memory =round((memory_get_usage()- $start_memory)/1024/1024, 2);
$finish_memory = round(memory_get_usage()/1024/1024, 2);
$pick_Memory = round(memory_get_peak_usage()/1024/1024, 2);
echo "Page loading time ".$result_time." seconds. Memory usage ".$memory." MB. ".$finish_memory." MB. Max memory usage ".$pick_Memory." MB.";
?>