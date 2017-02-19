<?php declare(strict_types = 1);
/**
 * @category     Controllers
 * @package      JenkinsPhpApp
 * @copyright    Copyright (c) 2017 Bentler Design (www.bricebentler.com)
 * @author       Brice Bentler <me@bricebentler.com>
 */

namespace BentlerDesign\Controllers;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DogsController implements ControllerProviderInterface
{
    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return \Silex\ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        /** @var \Silex\ControllerCollection $collection */
        $collection = $app['controllers_factory'];

        $collection->get('/', [$this, 'listDogs']);
        $collection->post('/', [$this, 'createDog']);

        return $collection;
    }

    public function listDogs(Application $app): JsonResponse
    {

    }

    public function createDog(Application $app, Request $request): JsonResponse
    {

    }
}
