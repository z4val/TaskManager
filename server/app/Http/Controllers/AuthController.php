<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use GuzzleHttp\Psr7\Message;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;
use Tymon\JWTAuth\Facades\JWTAuth;

use function Pest\Laravel\json;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed', 
        ]);

        if($validated->fails())
        {
            $data = [
                'message' => 'Validate errors',
                'errors' => $validated->errors()
            ];

            return response()->json($data, 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        $data = [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];

        return response()->json($data, 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized. Incorrect credentials'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        try {
            return response()->json(auth('api')->user());
        } catch(\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 401);
        }
    }

    public function logout()
    {
        try {
            auth('api')->logout();
            return response()->json(['message' => 'Successfully logged out']);
        } catch(JWTException $ex) {
            return response()->json(['error' => 'Logout error'], 401);
        }
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user()
        ]);
    }
}
