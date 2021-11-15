<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        return $this->jsonResponseWithToken($token);
    }

    public function register(RegisterRequest $request)
    {
        $registerData = $request->validated();
        $registerData['password'] = Hash::make($registerData['password']);
        $user = User::create($registerData);
        $token = Auth::login($user);

        return $this->jsonResponseWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(['user' => auth('api')->user() ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();
        return response()->json([
            'logout' => true
        ]);
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

    public function update(Request $request)
    {
        $user = User::findOrFail($request['user_id']);

        $data = $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'password' => 'required|string|min:6',
            "password_confirmation" => 'required|same:password',
        ]);

            $user->first_name = $data['first_name'];
            $user->last_name = $data['last_name'];
            $user->password =  Hash::make($data['password']);
    

            $user->save();
            return $user;       
    }

    private function jsonResponseWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'user' => Auth::user()
        ]);
    }
}