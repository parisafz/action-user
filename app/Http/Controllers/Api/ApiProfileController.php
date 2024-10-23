<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * کلاس ApiProfileController برای مدیریت پروفایل کاربر.
 */
class ApiProfileController extends Controller
{
    /**
     * نمایش اطلاعات پروفایل کاربر جاری.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        return Auth::user();
    }

    /**
     * به‌روزرسانی اطلاعات پروفایل کاربر جاری.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'username' => 'string',
            'first_name' => 'string',
            'last_name' => 'string',
            'email' => 'string|email|unique:users,email,' . Auth::id(),
            'password' => 'string|min:8',
        ]);

        $user = Auth::user();

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user], 200);
    }

    /**
     * ورود به سیستم با استفاده از ایمیل و رمزعبور.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $login = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // sanctum
        // if (!Auth::attempt($login)) {
        //     return response()->json(['message' => 'Failed: invalid login info'], 401);
        // }

        // JWT
        if (!$token = JWTAuth::attempt($login)) {
            return response()->json(['message' => 'Failed: invalid login info'], 401);
        }

        $user = Auth::user();

        // sanctum
        // $token = $user->createToken('accessToken')->plainTextToken;

        return response()->json([
            'message' => 'Successfully logged in.',
            'user' => Auth::user(),
            'token' => $token
        ]);
    }

    /**
     * خروج از سیستم.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        // sanctum
        // $user = Auth::user();
        // $user->tokens()->delete();

        // JWT
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Successfully logged out.'], 200);
    }
}
