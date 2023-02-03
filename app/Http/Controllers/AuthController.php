<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Авторизация пользователя по email и password
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function loginUser(LoginRequest $request): JsonResponse
    {
        if(Auth::attempt($request->only(['email', 'password']))) {
            $user = User::where('email', $request->email)->first();

            return response()->json([
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ]);
        }

        return response()->json([
            'message' => 'Указанные данные не найдены в системе.',
        ], 401);
    }
}
