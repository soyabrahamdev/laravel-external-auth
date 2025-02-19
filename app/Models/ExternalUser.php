<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ExternalUser extends Model implements Authenticatable{
    use HasApiTokens, HasFactory;

    protected $table = 'users_external';

    protected $fillable = [ 'username' ];

    protected $attributes = [
        'nombre'    => '',
        'apellidos' => '',
        'correo'    => '',
        'puesto'    => ''
    ];

    // * Dependerá si quieren obtener estos datos
    // protected $appends = [ 'current_login', 'expiration_login' ];

    protected $hidden = [ 'id', 'created_at', 'updated_at' ];

    public function getAuthIdentifierName(){
        return 'username';
    }

    public function getAuthIdentifier(){
        return $this->attributes['username'] ?? null;
    }

    public function getAuthPassword(){
        return $this->attributes['password'] ?? null;
    }

    public function getRememberToken(){
        return null;
    }

    public function setRememberToken($value){
        //
    }

    public function getRememberTokenName(){
        return null;
    }

    // Custom
    public function getCurrentLoginAttribute(){
        return $this->currentAccessToken()->created_at ?? null;
    }
    public function getExpirationLoginAttribute(){
        return $this->currentAccessToken()->expires_at ?? null;
    }
}
