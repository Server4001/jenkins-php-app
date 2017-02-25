<?php declare(strict_types=1);
/**
 * @category     Public
 * @package      JenkinsPhpApp
 * @copyright    Copyright (c) 2017 Bentler Design (www.bricebentler.com)
 * @author       Brice Bentler <me@bricebentler.com>
 */

if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', dirname(__DIR__));
}

require_once PROJECT_ROOT . '/app/Bootstrap.php';

use BentlerDesign\Controllers\DogsController;
use BentlerDesign\Controllers\IndexController;
use BentlerDesign\Providers\DatabaseProvider;
use Dotenv\Dotenv;
use Silex\Application;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\HttpFoundation\Request;

$config = new Dotenv(PROJECT_ROOT . '/config', '.env');
$config->load();

if (getenv('LOG_FILE') === false) {
    throw new Exception('Missing LOG_FILE environment variable.');
}
if (getenv('LOG_LEVEL') === false) {
    throw new Exception('Missing LOG_LEVEL environment variable.');
}

if ((int)getenv('ENABLE_CODECEPTION') === 1) {
    // Codeception code coverage tool.
    define('C3_CODECOVERAGE_ERROR_LOG_FILE', getenv('LOG_FILE'));
    require_once PROJECT_ROOT . '/c3.php';
}

$app = new Application();

$app->register(new DatabaseProvider());
$app->register(new MonologServiceProvider(), [
    'monolog.logfile' => getenv('LOG_FILE'),
    'monolog.level' => getenv('LOG_LEVEL'),
]);

$app->register(new TwigServiceProvider(), [
    'twig.path' => PROJECT_ROOT . '/lib/Views',
]);

$app->mount('/', new IndexController());
$app->mount('/dogs/v1', new DogsController());

$app->before(function (Request $request) {
    $contentType = $request->headers->get('Content-Type');

    if (is_null($contentType)) {
        $contentType = '';
    }
    if (0 === strpos($contentType, 'application/json')) {
        $data = json_decode($request->getContent(), true);

        $request->request->replace(is_array($data) ? $data : []);
    }
});

$app->run();
