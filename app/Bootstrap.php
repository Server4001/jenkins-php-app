<?php declare(strict_types=1);
/**
 * @category     App
 * @package      JenkinsPhpApp
 * @copyright    Copyright (c) 2017 Bentler Design (www.bricebentler.com)
 * @author       Brice Bentler <me@bricebentler.com>
 */

require_once PROJECT_ROOT . '/vendor/autoload.php';

set_error_handler(function($errorNumber, $errorString, $errorFile, $errorLine) {
    throw new ErrorException($errorString, 0, $errorNumber, $errorFile, $errorLine);
});

error_reporting(E_ALL);
