<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Zhiyi\Plus\FileStorage\StorageInterface;

class CreateTask extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Create a upload task.
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\FileStorage\StorageInterface $storage
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request, StorageInterface $storage)
    {
        $task = $storage->createTask($request);

        return new JsonResponse([
            'uri' => $task->getUri(),
            'method' => $task->getMethod(),
            'headers' => $task->getHeaders(),
            'form' => $task->getForm(),
            'file_key' => $task->getFileKey(),
            'node' => $task->getNode(),
        ], JsonResponse::HTTP_CREATED);
    }
}
