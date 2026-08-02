<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Success Response
     */
    protected function success(
        mixed $data = null,
        string $message = 'Success',
        int $status = 200
    ): JsonResponse {

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Error Response
     */
    protected function error(
        string $message = 'Something went wrong.',
        mixed $errors = null,
        int $status = 500
    ): JsonResponse {

        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

    /**
     * Paginated Response
     */
    protected function paginated(
        $collection,
        string $message = 'Records fetched successfully.'
    ): JsonResponse {

        return response()->json([
            'success' => true,
            'message' => $message,

            'data' => $collection->items(),

            'pagination' => [

                'current_page' => $collection->currentPage(),

                'last_page' => $collection->lastPage(),

                'per_page' => $collection->perPage(),

                'total' => $collection->total(),

                'from' => $collection->firstItem(),

                'to' => $collection->lastItem(),

                'links' => $collection->linkCollection(),

                'next_page_url' => $collection->nextPageUrl(),

                'prev_page_url' => $collection->previousPageUrl(),

            ],
        ]);
    }
}