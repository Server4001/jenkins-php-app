<?php declare(strict_types=1);
/**
 * @category     Bootstrappers
 * @package      JenkinsPhpApp
 * @copyright    Copyright (c) 2017 Bentler Design (www.bricebentler.com)
 * @author       Brice Bentler <me@bricebentler.com>
 */

if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', dirname(__DIR__, 2));
}

require_once PROJECT_ROOT . '/app/Bootstrap.php';

$config = new \Dotenv\Dotenv(PROJECT_ROOT . '/config', '.env');
$config->load();

$config = [
    'DB_HOST' => getenv('DB_HOST'),
    'DB_PORT' => getenv('DB_PORT'),
    'DB_USER' => getenv('DB_USER'),
    'DB_PASS' => getenv('DB_PASS'),
    'CODECEPT_MYSQL_DBNAME' => getenv('CODECEPT_MYSQL_DBNAME'),
];

foreach ($config as $key => $value) {
    if ($value === false) {
        throw new Exception("Missing database config: {$key}.");
    }
}

return $config;
