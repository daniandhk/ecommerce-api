<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Login Failed!',
            ]);
        }

        $userRole = $user->role()->first();
        if ($userRole) {
            $this->scope = $userRole->role;
        } else {
            $user->role()->create([
                'role' => 'user'
            ]);
            $this->scope = 'user';
        }
        $token = $user->createToken($user->email . '-' . now(), [$this->scope]);

        return response()->json([
            'success' => true,
            'message' => 'Login Success!',
            'data'    => $user,
            'role'    => $userRole->role,
            'token'   => $token->accessToken,
        ]);
    }

    public function logout(Request $request)
    {
        $removeToken = $request->user()->tokens()->delete();

        if ($removeToken) {
            return response()->json([
                'success' => true,
                'message' => 'Logout Success!',
            ]);
        }
    }
}
