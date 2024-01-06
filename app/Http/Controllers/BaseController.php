<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    public function sendResponse($data,$message): JsonResponse
    {
        $response=[
            'data' => $data,
            'message' =>$message,
            'status_code' => 200
        ];
        return response()->json($response);
    }

    public function sendMessage($message): JsonResponse
    {
        $response=[
            'message' =>$message,
            'status_code' => 200
        ];
        return response()->json($response);
    }

    public function sendError($error , $errorMessage=[],  $code = 404): JsonResponse
    {
        $response=[
            'data' => $errorMessage,
            'message' =>$error,
            'status_code' => $code,
        ];
        return response()->json($response ,$code);
    }

    protected function respondWithToken($token, $user = null): array
    {
        return [
            'access_token' => $token,
            'profile' => (new UserResource($user))->jsonSerialize(),
        ];
    }

    protected function response($message, $data = null, $meta = null, $code = 200): JsonResponse
    {
        $response = ['message' => $message, 'status_code' => $code];

        if ($meta)
            $response = array_merge(['meta' => $meta], $response);

        if (!is_null($data))
            $response = array_merge(['data' => $data], $response);

        return response()->json($response, $code);
    }
}
