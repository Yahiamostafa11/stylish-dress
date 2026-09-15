<?php
$root = __DIR__;
$path = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
if ($path !== '/' && file_exists($root . $path) && !is_dir($root . $path)) {
    return false;
}
require_once $root . '/index.php';
