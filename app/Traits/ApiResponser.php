<?php


namespace App\Traits;


use Symfony\Component\HttpFoundation\Response;

trait ApiResponser
{
    /**
     * @param string $message
     * @param int $httpCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponseWithMessage($message = '', $httpCode = Response::HTTP_OK): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => $message,
        ], $httpCode)->setStatusCode($httpCode);
    }

    /**
     * @param array $data
     * @param int $httpCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponseWithData($data = [], $httpCode = Response::HTTP_OK): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'data' => $data,
        ])->setStatusCode($httpCode);
    }


    /**
     * @param string $message
     * @param int $httpCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponseWithMessage($message = '', $httpCode = Response::HTTP_BAD_REQUEST): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => $message,
        ], $httpCode)->setStatusCode($httpCode);
    }
    
    /**
     * @param $data
     * @param int $httpCode
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function successResponse($data, $httpCode = Response::HTTP_OK): \Illuminate\Http\JsonResponse
    {
        return response()->json($data, $httpCode)->setStatusCode($httpCode);  
    }
}
