<?php


namespace App\Traits;


use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponser
{
    /**
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($message = '', $code = Response::HTTP_OK)
    {
        return response()->json([
            'message' => $message,
            'code' => $code,
            'success' => true,
        ], $code);
    }

    /**
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorRespose($message = '', $code = Response::HTTP_BAD_REQUEST)
    {
        return response()->json([
            'message' => $message,
            'code' => $code,
            'success' => false,
        ], $code);
    }
}
