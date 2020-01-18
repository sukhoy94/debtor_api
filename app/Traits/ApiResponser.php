<?php


namespace App\Traits;


use Symfony\Component\HttpFoundation\Response;

trait ApiResponser
{
    /**
     * @param string $message
     * @param int $http_code
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($message = '', $http_code = Response::HTTP_OK)
    {
        return response()->json([
            'message' => $message,
        ], $http_code)->setStatusCode($http_code);
    }

    /**
     * @param array $data
     * @param int $http_code
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponseWithData($data = [], $http_code = Response::HTTP_OK)
    {
        return response()->json([
            'data' => $data,
        ])->setStatusCode($http_code);
    }


    /**
     * @param string $message
     * @param int $http_code
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse($message = '', $http_code = Response::HTTP_BAD_REQUEST)
    {
        return response()->json([
            'message' => $message,
        ], $http_code)->setStatusCode($http_code);
    }
}
