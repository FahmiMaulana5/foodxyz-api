<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'alamat' => 'required',
            'telpon' => 'required',
            'username' => 'required|unique:tbl_user,username|regex:/^[a-zA-Z0-9._]+$/',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'erros' => $validator->errors()
            ], 422);
        }

        $user = User::create($request->only('nama', 'alamat', 'telpon', 'username', 'password'));
        $user['token'] = $user->createToken('user_token')->plainTextToken;

        return response()->json([
            'message' => 'Register success',
            'user' => $user->only('nama', 'alamat', 'telpon', 'username', 'token'),
        ]);
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('username', 'password'))) {
            return response()->json([
                'message' => 'Username or password incorrect'
            ], 401);
        }

        $user = Auth::user();
        $user['token'] = $user->createToken('user_token')->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'user' => $user->only('nama', 'alamat', 'telpon', 'username', 'token'),
        ]);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout success'
        ]);
    }

}
