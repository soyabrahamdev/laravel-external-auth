<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\ExternalUser;
use Illuminate\Support\Facades\Http;

class ExternalUserProvider implements UserProvider{
    public function retrieveByCredentials(array $credentials){
        $response = Http::post(env('WS_AUTH'), [
            // Pueden cambiar las variables de autenticación
            'usuario'  => $credentials['username'],
            'password' => $credentials['password'],
        ]);

        if ($response->failed()) return null;

        $data = $response->json();

        // * Aquí se pone la lógica de la autenticación
        if($data['estatus'] == 0){
            if( !$eUser = ExternalUser::firstWhere('username', $credentials['username']) ){
                $eUser = ExternalUser::create([ 
                    'username' => $credentials['username']
                ]);
            }

            // Datos a usarse
            $datos = $data['datos'];
            $eUser->nombre    = $datos['nombre'] ?? '';
            $eUser->apellidos = $datos['apellidos'] ?? '';
            $eUser->correo    = $datos['correo'] ?? '';
            $eUser->puesto    = $datos['puesto'] ?? '';

            return $eUser;
        }else{
            return null;
        }
        // *********************** */
    }

    public function validateCredentials(Authenticatable $user, array $credentials){
        return $user->username === $credentials['username'];
    }

    public function retrieveById($identifier){
        return null; // No se usa
    }

    public function retrieveByToken($identifier, $token){
        return null; // No se usa
    }

    public function updateRememberToken(Authenticatable $user, $token){
        // No aplicable
    }
}
