<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //register a new user
    public function register(Request $request){
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'role' => 'user',
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('mytoken', ['user'])->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 200);
    }

    //register a new admin
    public function register_admin(Request $request){
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'role' => 'admin',
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('mytoken', ['admin'])->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 200);
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
    
        return response([
            'message'=> 'Logout'
        ]);
    }

    public function login(Request $request){
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if(!$user || !Hash::check($fields['password'], $user->password)){
            return response([
                'message' => 'Invalid Credentials'
            ]);
        }
        $role = $user->role;
        if($role == 'admin')
        {
            $token = $user->createToken('mytoken', ['admin'])->plainTextToken;
        }else if($role == 'user'){
            $token = $user->createToken('mytoken', ['user'])->plainTextToken;
        }

        
        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response($response, 200);
    }
}
