<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserAuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:8'
        ]);

        if($validator->fails()){
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $userData = $validator->validated();
        $userData['password'] = Hash::make($userData['password']);

        $user = User::create($userData);

        return response()->json(new UserResource($user), 200);
    }

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $loginData = $validator->validated();

        $foundUser = User::where('email', $loginData['email'])->first();

        if(!$foundUser){
            return response()->json([
                'errors' => [
                    'Authentication failed!'
                ]
            ], 401);
        }

        if(!Hash::check($loginData['password'], $foundUser->password)){
            return response()->json([
                'errors' => [
                    'Authentication failed!'
                ]
            ], 401);
        }

        return response()->json(new UserResource($foundUser), 200);
    }
}
