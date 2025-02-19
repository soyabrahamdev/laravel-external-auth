<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Str;
use App\Models\ExternalUser;

class AuthController extends Controller{

    public function login(Request $request){
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // $user = Auth::user();
        // * Crear un token de Sanctum
        $exp = env('WS_TOKEN_EXPIRATION', 3600);
        $token = Auth::user()->createToken(
                'api-token',
                ['*'],
                now()->addSeconds($exp)
            )->plainTextToken;

        return response()->json([
            'user'  => Auth::user()->makeHidden(['current_login', 'expiration_login']),
            'token' => $token,
            'exp'   => $exp
        ]);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada']);
    }

    public function logoutAllSesions(Request $request){
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Sesiones cerradas']);
    }
}
