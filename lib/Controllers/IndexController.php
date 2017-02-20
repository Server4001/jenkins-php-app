<?php declare(strict_types = 1);
/**
 * @category     Controllers
 * @package      JenkinsPhpApp
 * @copyright    Copyright (c) 2017 Bentler Design (www.bricebentler.com)
 * @author       Brice Bentler <me@bricebentler.com>
 */

namespace BentlerDesign\Controllers;

use BentlerDesign\Models\Dogs;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;

class IndexController implements ControllerProviderInterface
{
    /**
     * @var null|Dogs
     */
    private $dogsModel = null;

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return \Silex\ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        $this->dogsModel = new Dogs($app['database']);

        /** @var \Silex\ControllerCollection $collection */
        $collection = $app['controllers_factory'];

        $collection->get('/', [$this, 'getIndex']);

        return $collection;
    }

    public function getIndex(Application $app): string
    {
        /** @var \Twig_Environment $twig */
        $twig = $app['twig'];
        $dogs = $this->dogsModel->listAllDogs();

        return $twig->render('index.twig', [
            'dogs' => $dogs,
        ]);
    }
}
