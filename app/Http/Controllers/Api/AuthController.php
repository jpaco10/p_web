<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function getUser(Request $request)
    {
        return $request->user();
    }

    public function generateToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciales incorrectas.',
                'errors' => [
                    'email' => ['Datos Incorrectos.'],
                ]
            ]);
        }

        return $user->createToken($request->device_name)->plainTextToken;
    }

    public function revokeToken(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return 'Tokens Eliminados';
    }

    public function register(Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'type' => 'required|string',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'type' => $request->input('type'),
        ]);

        if($request->input('type') == "taller"){
            Workshop::create([
                'user_id' => $user->id
            ]);
        }

        if($request->input('type') == "cliente"){
            Client::create([
                'user_id' => $user->id
            ]);
        }

        return $user->createToken('AuthToken')->plainTextToken;
    }
}
