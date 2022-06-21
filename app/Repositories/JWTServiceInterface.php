<?php 
namespace App\Repositories;

use Illuminate\Http\JsonResponse;

interface JWTServiceInterface {

    public function respondWithToken($token=null, $msg = null): JsonResponse;

}