<?php declare(strict_types = 1);
/**
 * @category     Providers
 * @package      JenkinsPhpApp
 * @copyright    Copyright (c) 2017 Bentler Design (www.bricebentler.com)
 * @author       Brice Bentler <me@bricebentler.com>
 */

namespace BentlerDesign\Providers;

use Exception;
use PDO;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class DatabaseProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $app A container instance
     *
     * @throws Exception
     */
    public function register(Container $app)
    {
        $config = [
            'DB_HOST' => getenv('DB_HOST'),
            'DB_PORT' => getenv('DB_PORT'),
            'DB_USER' => getenv('DB_USER'),
            'DB_PASS' => getenv('DB_PASS'),
            'DB_NAME' => getenv('DB_NAME'),
        ];

        foreach ($config as $key => $value) {
            if (!is_string($value) || strlen($value) < 1) {
                throw new Exception("Missing/invalid MySQL environment variable: {$key}.");
            }
        }

        $app['database'] = function() use ($config) {
            $dsn = "mysql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_NAME']}";

            return new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        };
    }
}
