<?php 
namespace App\Services;

use App\Repositories\JWTServiceInterface;
use Illuminate\Http\JsonResponse;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class JWTService implements JWTServiceInterface {

    public function respondWithToken($token = null, $msg = null): JsonResponse
    {
        return response()->json([
            'success'   => true,
            'message'   => $msg,
            'user'      => JWTAuth::user(),
            'authorization' => [
                'token'     => $token,
                'type'      => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]
        ], 200);
    }

}