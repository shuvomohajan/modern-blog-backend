<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function apiResponse(int $statusCode, string $statusMessage, $data = []): JsonResponse
    {
        $data['message'] = $statusMessage;
        return response()->json($data, $statusCode);
    }

    public function apiResponseResourceCollection(int $statusCode, string $statusMessage, object $resourceCollection): JsonResponse
    {
        $resourceCollection = $resourceCollection->additional([
            'message' => $statusMessage
        ])->response()->getData();
        return response()->json($resourceCollection, $statusCode);
    }
}
