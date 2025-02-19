<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Instrucciones Dev

1. Revisar los archivos `database/migrations/___external-auth.php` y `app/Models/ExternalUser.php` para la información de la base de datos esté correcta para su uso.
    - Se pueden agregar campos a l DB y al modelo para guardar datos que se necesiten que sean persistentes. Ej:

_**Archivo de migración:**_
```
Schema::create('users_external', function (Blueprint $table) {
    . . .
    $table->string('data')->nullable(); // Ejemplo
    . . .
});
```
_**Modelo:**_
```
class ExternalUser extends Model implements Authenticatable{
    . . .
    protected $fillable = [ 'username', 'data' ];
    . . .
}
```

2. Ejecutar el comando de migration:
```
php artisan migrate
```

3. Después de la revisión anterior, revisar el archivo `app/Providers/ExternalUserProvider.php` y realizar los cambios necesarios dependiendo del ajuste pasado.
 
4. Abrir el archivo `.env.example` y crear el archivo `.env` editando las líneas `WS_AUTH` y `WS_TOKEN_EXPIRATION` con el Web Service/Enpoint de inicio de sesión externo y la expiración en **segundos** del token.
 
5. Correr el Scheduling de Laravel para la eliminación de tokens vencidos:
```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

6. La rutas del auth de prueba son:
```
POST /api/login
GET /api/user -> with Auth Bearer Token
POST /api/logout -> with Auth Bearer Token
```
_Como apoyo, se suben el archivo postman para su ejecución y pruebas, archivos dentro de la carpeta `postman`_

## Instrucciones Prod

1. Borrar carpeta `postman` antes de publicar.

2. Aplicar los cambios en el archivo `.env`:
    - `APP_ENV=production`
    - `APP_DEBUG=false`

3. Optimizar el Composer Autoloader: 
    - `composer dump-autoload --optimize`

4. Optimizar Laravel (**No usar durante desarrollo/dev**):
    - `php artisan config:cache`
    - `php artisan route:cache`
    - `php artisan view:cache`
    - `php artisan optimize`

Si se requiere más información de publicar a producción, [leer este artículo de ayuda](https://bayramblog.medium.com/what-steps-should-one-take-to-make-a-laravel-app-ready-for-production-mode-935ee9192c52).