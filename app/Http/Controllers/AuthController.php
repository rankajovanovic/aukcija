<?php

namespace App\Http\Controllers;


use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
          ];
      
          $token = auth('api')->attempt($credentials);
      
          if (!$token) {
            return response()->json(['message' => 'Unauthenticated'], 401);
          }
      
          return [
            'token' => $token,
            'user' => auth('api')->user()
          ];
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $token = auth('api')->login($user);
        
        return [
        'token' => $token,
        'user' => $user
        ];
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
  
    public function refreshToken()
    {
        $token = Auth::refresh();
        return [
            'token' => $token
        ];
    }
    public function authUser()
    {
        $user = auth('api')->user();
        return $user;
    }

}