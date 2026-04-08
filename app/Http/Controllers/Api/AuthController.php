<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // return Hash::make($request->password);
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:60',
            'email' => 'required|unique:users,email',
            'password' => [
                'required',
                'min:6',
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ]

        ],
        [
            "password.regex" => "Password must contain at least one lowercase letter, one uppercase letter, one digit, and one special character.",
        ]
        );

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => $validator->errors()
            ]);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        $token = $user->createToken($user->email)->plainTextToken;
        return response()->json([
            "success" => true,
            "token" => $token,
            "message" => "User registration successfully!"
        ]);
    }

    public function login(Request $request)
    {
        $user = User::where("email", $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                "success" => false,
                "token" => null,
                "message" => "Invalid credentials."
            ]);
        }

        $token = $user->createToken($user->email)->plainTextToken;

        return response()->json([
            "success" => true,
            "token" => $token,
            "message" => "User login successfully!"
        ]);
    }

    public function profile()
    {
        $user = Auth::user();
        return new UserResource($user);
    }

    public function login_response()
    {
        return response()->json([
            "success" => false,
            "token" => null,
            "message" => "User not loggedIn!"
        ]);
    }

    public function logout()
    {
        $user = User::find(Auth::user()->id);
        $user->tokens()->delete();
        return response()->json([
            "success" => true,
            "token" => null,
            "message" => "User logout successfully!"
        ]);
    }
}
