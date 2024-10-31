<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Register
    public function register(Request $request)
    {

        $user = User::create($request->all());

        $token = $user->createToken('auth')->plainTextToken;

        if ($request->hasFile('company_logo')) {
            $user->addMediaFromRequest('company_logo')->toMediaCollection();
        }

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    // Login
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة',
            ], 401);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $token = $user->createToken('auth')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user,
            ], 200);
        }
    }

    // Logout
    public function logout()
    {
        $user = auth()->user();
        $user->tokens()->delete();
        return response(['message' => 'تم تسجيل الخروج بنجاح'], 200);
    }

    public function show()
    {
        $user = auth()->user();
        $user->load('media');
        return response()->json($user, 200);
    }
}
