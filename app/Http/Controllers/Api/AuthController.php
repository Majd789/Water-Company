<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['me' ,'logout']);
    }

   public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'بيانات الدخول غير صحيحة'], 401);
        }

        $user = Auth::user();

        // حذف كل التوكنات السابقة (اختياري)
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'roles' => method_exists($user, 'getRoleNames') ? $user->getRoleNames() : [],
                'permissions' => method_exists($user, 'getAllPermissions') ? $user->getAllPermissions()->pluck('name') : [],
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $accessToken = $request->bearerToken();
        if ($accessToken) {
            $token = PersonalAccessToken::findToken($accessToken);
            if ($token) {
                $token->delete();
            }
        }

        return response()->json(['message' => 'تم تسجيل الخروج بنجاح']);
    }


     public function me(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email ?? null,
            'roles' => method_exists($user, 'getRoleNames') ? $user->getRoleNames() : [],
            'permissions' => method_exists($user, 'getAllPermissions') ? $user->getAllPermissions()->pluck('name') : [],
        ]);
    }
}
