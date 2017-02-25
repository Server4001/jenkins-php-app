<?php
// This is global bootstrap for autoloading
if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', dirname(__DIR__));
}

require_once PROJECT_ROOT . '/vendor/autoload.php';
