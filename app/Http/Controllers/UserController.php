<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->user();
        $role = $user->role()->first()->role;
        $token = $request->user()->createToken($request->user()->email . '-' . now(), [$role]);

        return response()->json([
            'success' => true,
            'message' => 'Get Profile Success!',
            'data'    => $user,
            'role'    => $role,
            'token'   => $token->accessToken,
        ]);
    }
}
