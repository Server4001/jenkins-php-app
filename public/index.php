<?php declare(strict_types=1);
/**
 * @category     Public
 * @package      JenkinsPhpApp
 * @copyright    Copyright (c) 2017 Bentler Design (www.bricebentler.com)
 * @author       Brice Bentler <me@bricebentler.com>
 */

define('PROJECT_ROOT', dirname(__DIR__));

require_once PROJECT_ROOT . '/app/Bootstrap.php';

set_error_handler(function($errorNumber, $errorString, $errorFile, $errorLine) {
    // error suppressed with @
    if (error_reporting() === 0) {
        return;
    }

    throw new ErrorException($errorString, 0, $errorNumber, $errorFile, $errorLine);
});

use BentlerDesign\Controllers\DogsController;
use BentlerDesign\Controllers\IndexController;
use BentlerDesign\Providers\DatabaseProvider;
use Dotenv\Dotenv;
use Silex\Application;
use Silex\Provider\MonologServiceProvider;

$config = new Dotenv(PROJECT_ROOT . '/config', '.env');
$config->load();

$app = new Application();

if (getenv('LOG_FILE') === false) {
    throw new Exception('Missing LOG_FILE environment variable.');
}
if (getenv('LOG_LEVEL') === false) {
    throw new Exception('Missing LOG_LEVEL environment variable.');
}

$app->register(new DatabaseProvider());
$app->register(new MonologServiceProvider(), [
    'monolog.logfile' => getenv('LOG_FILE'),
    'monolog.level' => getenv('LOG_LEVEL'),
]);

$app->mount('/', new IndexController());
$app->mount('/dogs/v1', new DogsController());

$app->run();
