<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
* AuthController handles the user registration and authentication requests.
 */
class AuthController extends Controller
{
    /**
     * Register a new user with the provided request data.
     *
     * @param Request $request Request instance containing name, email and password.
     * @return JsonResponse JSON response containing user data.
     */
    public function register(Request $request): JsonResponse
    {
        $registerForm = new RegisterUserRequest();
        $validator = Validator::make($request->all(), $registerForm->rules());
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        try {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json(['user' => $user], 201);
        } catch(Exception $e){
            Log::error('Registration Failed', ['exception' => $e->getMessage()]);
            return response()->json(['message' => 'Registration failed. Please try again later.'], 500);
        }
    }

    /**
     * Authenticate a user and return a JWT token.
     *
     * @param Request $request Request instance containing email and password.
     * @return JsonResponse JSON response containing JWT token.
     */
    public function login(Request $request): JsonResponse
    {
        try {
        $credentials = $request->only('email', 'password');

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
        } catch(Exception $e){
            Log::error('Login Failed', ['exception' => $e->getMessage()]);
            return response()->json(['message' => 'Login failed. Please try again later.'], 500);
        }
    }

    /**
     * Create a new JWT token.
     *
     * @param string $token JWT token.
     * @return JsonResponse JSON response containing the JWT token.
     */
    protected function createNewToken($token): JsonResponse
    {
        try{
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
        } catch (Exception $e) {
            Log::error('Error on creating token', ['exception' => $e->getMessage()]);
            return response()->json(['message' => 'Error occurred. Please try again later.'], 500);
        }
    }

    /**
     * Get the authenticated user.
     *
     * @param Request $request Request instance containing the JWT token.
     * @return JsonResponse JSON response containing user data.
     */
    public function getUser(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (! $user) {
                return response()->json([
                    'error' => 'user_not_found',
                    'description' => 'No user associated with the provided token.'
                ], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['error' => 'token_expired', 'description' => $e->getMessage()], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'token_invalid', 'description' => $e->getMessage()], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'token_absent', 'description' => $e->getMessage()], $e->getStatusCode());
        }
        return response()->json(compact('user'));
    }
}
