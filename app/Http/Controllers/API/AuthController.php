<?php

namespace App\Http\Controllers\API;

use App\Models\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Services\JWTService;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public $JWT_Service;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register','me', 'logout', 'refresh']]);

        $this->JWT_Service = new JWTService;
    }


    public function login(AuthLoginRequest $request)
    {
        try {

            $credentials = $request->validated();

            $token = JWTAuth::attempt($credentials);
            if (!$token) 
                throw new \Exception("You are Unauthorized!", 403);

            return $this->JWT_Service->respondWithToken($token, 'You are loggedIn!');


        } catch (\Throwable $th) {

            return response()->json([
                'success'   => false,
                'message' => $th->getMessage(),
            ], 403);
        }
    }

    public function register(AuthRegisterRequest $request)
    {
       try {

            $data               = $request->validated();
            $data['password']   = Hash::make($request->password);
           
            $user   = User::create($data);
            if(!$user)
                throw new \Exception("Unable to create User!", 403);

            return response()->json([
                'success'   => true,
                'message'   => 'User created successfully',
                'user'      => $user
            ], 201);

       } catch (\Throwable $th) {
     
            return response()->json([
                'success'   => false,
                'message'   => $th->getMessage(),
            ], 403);
       }
    }


    public function logout()
    {
        auth()->logout();
        
        return response()->json([
            'success'   => true,
            'message' => 'Successfully logged out',
        ]);
    }


    public function me()
    {
        return response()->json(auth()->user());
    }

    public function refresh()
    {
        return $this->JWT_Service->respondWithToken(auth()->refresh());
    }




    

}
