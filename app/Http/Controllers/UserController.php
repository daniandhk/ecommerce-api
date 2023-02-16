<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function index()
    {
        //get data from table users
        $data = User::latest()->get();

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'List Data User',
            'data'    => $data
        ], 200);
    }

    /**
     * show
     *
     * @param  mixed $id
     * @return void
     */
    public function show($id)
    {
        //find user by ID
        try {
            $data = User::findOrfail($id);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User Not Found',
            ], 404);
        }

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data User',
            'data'    => $data
        ], 200);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $user
     * @return void
     */
    public function update(Request $request, $id)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email',
            'role'   => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //find user by ID
        try {
            $user = User::findOrfail($id);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User Not Found',
            ], 404);
        }

        if ($user->email != $user->email) {
            $validator = Validator::make($request->all(), [
                'email'   => 'unique:users',
            ]);

            //response error validation
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
        }

        $user->update([
            'name'     => $request->name,
            'email'   => $request->email,
        ]);

        $user->role()->update([
            'role' => $request->role,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User Updated',
            'data'    => $user
        ], 200);
    }

    /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        //find user by ID
        try {
            $user = User::findOrfail($id);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User Not Found',
            ], 404);
        }

        $user->orders()->delete();
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User Deleted',
        ], 200);
    }
}
