<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Registrasi Admin baru
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'admin_username' => 'required|string|max:255|unique:admins,admin_username',
            'admin_password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to register. Please check your input data',
                'data'    => null,
                'errors'  => $validator->errors()
            ], 400);
        }

        $data = $validator->validated();
        $data['admin_password'] = Hash::make($data['admin_password']);

        $admin = Admin::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Successfully registered.',
            'data'    => $admin
        ], 201);
    }

    /**
     * Login Admin via API
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'admin_username' => 'required|string',
            'admin_password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to login. Please check your input data',
                'data'    => null,
                'errors'  => $validator->errors()
            ], 400);
        }

        $admin = Admin::where('admin_username', $request->admin_username)->first();

        if (!$admin || !Hash::check($request->admin_password, $admin->admin_password)) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to login. Wrong username or password',
                'data'    => null,
            ], 401);
        }

        // ✅ Ganti createToken() dengan JWT
        $token = JWTAuth::fromUser($admin);

        return response()->json([
            'success' => true,
            'message' => 'Successfully login.',
            'data'    => $admin,
            'token'   => $token
        ], 200);
    }

    /**
     * Get admin profile
     */
    public function me()
    {
        $admin = JWTAuth::parseToken()->authenticate();
        return response()->json($admin);
    }

    /**
     * Logout Admin
     */
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Successfully logged out']);
    }
}
