<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email',
            'password'  => 'required|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::withoutTrashed()->where('email', $request->email)->first();
        if ($user) {
            $user->restore();

            $user->update([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password)
            ]);

            if ($request->has('role')) {
                $user->role()->update([
                    'role' => $request->role
                ]);
            } else {
                $user->role()->update([
                    'role' => 'user'
                ]);
            }
        } else {
            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password)
            ]);

            if ($request->has('role')) {
                $user->role()->create([
                    'role' => $request->role
                ]);
            } else {
                $user->role()->create([
                    'role' => 'user'
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Register Success!',
            'data'    => $user
        ]);
    }
}
