<?php declare(strict_types = 1);
/**
 * @category     Controllers
 * @package      JenkinsPhpApp
 * @copyright    Copyright (c) 2017 Bentler Design (www.bricebentler.com)
 * @author       Brice Bentler <me@bricebentler.com>
 */

namespace BentlerDesign\Controllers;

use BentlerDesign\Models\Dogs;
use BentlerDesign\Validators\CreateRequestValidator;
use Exception;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DogsController implements ControllerProviderInterface
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

        $collection->get('', [$this, 'listDogs']);
        $collection->post('', [$this, 'createDog']);
        $collection->get('/{dogId}', [$this, 'getDog']);
        $collection->put('/{dogId}', [$this, 'updateDog']);
        $collection->patch('/{dogId}', [$this, 'updateDog']);
        $collection->delete('/{dogId}', [$this, 'deleteDog']);

        return $collection;
    }

    public function listDogs(): JsonResponse
    {
        $dogs = $this->dogsModel->listAllDogs();

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'dogs' => $dogs,
            ],
        ]);
    }

    public function createDog(Application $app, Request $request): JsonResponse
    {
        /** @var \Psr\Log\LoggerInterface $logger */
        $logger = $app['monolog'];
        $validator = new CreateRequestValidator();

        if (!$validator->valid($request)) {
            return new JsonResponse([
                'status' => 'fail',
                'data' => [
                    'errors' => $validator->getErrors(),
                ],
            ], 400);
        }

        try {
            $dogId = $this->dogsModel->createDog($request->request->get('name'), $request->request->get('breed'));
        } catch (Exception $e) {
            $logger->error(json_encode([
                'error' => 'exception_thrown',
                'exception_message' => $e->getMessage(),
                'exception_code' => $e->getCode(),
                'exception_trace' => $e->getTraceAsString(),
            ]));

            return new JsonResponse([
                'status' => 'error',
                'data' => [
                    'message' => $e->getMessage(),
                ],
            ], 500);
        }

        $statusCode = 201;
        $headers = ['Location' => $request->getUri() . $dogId];
        $data = [
            'status' => 'success',
            'data' => [
                'dog_id' => $dogId,
            ],
        ];

        return new JsonResponse($data, $statusCode, $headers);
    }

    public function getDog($dogId): JsonResponse
    {
        $dog = $this->dogsModel->getDog((int)$dogId);

        if (count($dog) < 1) {
            return new JsonResponse([
                'status' => 'fail',
                'data' => [
                    'message' => 'Unable to find dog.',
                ],
            ], 404);
        }

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'dog' => $dog,
            ],
        ]);
    }

    public function updateDog($dogId, Request $request): JsonResponse
    {
        $columns = [
            'name' => $request->request->get('name'),
            'breed' => $request->request->get('breed'),
        ];

        $updated = $this->dogsModel->updateDog((int)$dogId, $columns);
        $dog = $this->dogsModel->getDog((int)$dogId);

        if (!$updated || !$dog) {
            return new JsonResponse([
                'status' => 'fail',
                'data' => [
                    'message' => 'Unable to update dog.',
                ],
            ], 404);
        }

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'dog' => $dog,
            ],
        ]);
    }

    public function deleteDog($dogId): JsonResponse
    {
        $deleted = $this->dogsModel->deleteDog((int)$dogId);

        if (!$deleted) {
            return new JsonResponse([
                'status' => 'fail',
                'data' => [
                    'message' => 'Unable to delete dog.',
                ],
            ], 404);
        }

        return new JsonResponse(null, 204);
    }
}
