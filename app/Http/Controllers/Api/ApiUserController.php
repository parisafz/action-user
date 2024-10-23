<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * کلاس ApiUserController برای مدیریت کاربران.
 */
class ApiUserController extends Controller
{

    /**
     * دریافت لیست تمامی کاربران.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return User::all();
    }

    /**
     * ایجاد کاربر جدید.
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\User
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $validated['password'] = bcrypt($validated['password']);


        return User::create($validated);
    }

    /**
     * نمایش اطلاعات کاربر با شناسه مشخص.
     *
     * @param string $id
     * @return \App\Models\User
     */
    public function show(string $id)
    {
        return User::findOrFail($id);
    }

    /**
     * به‌روزرسانی اطلاعات کاربر.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => 'string',
            'first_name' => 'string',
            'last_name' => 'string',
            'email' => 'string|email|unique:users',
            'password' => 'string|min:8',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);
        return response()->json(['message' => 'User updated successfully.', 'user' => $user], 200);
    }

    /**
     * حذف کاربر با شناسه مشخص.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'record successfully deleted.'], 200);
    }
}
