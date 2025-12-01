<?php

namespace App\Models;

use Illuminate\Database\Capsule\Manager as Capsule;


class Database
{
    public function __construct()
    {
        try {
            $capsule = new Capsule;
            error_log("ENV HOST=" . ($_ENV['DB_HOST'] ?? 'NO CARGADO'));

            $capsule->addConnection([
                'driver'    => 'mysql',
                'host'      => $_ENV['DB_HOST'],
                'port'      => $_ENV['DB_PORT'],
                'database'  => $_ENV['DB_DATABASE'],
                'username'  => $_ENV['DB_USERNAME'],
                'password'  => $_ENV['DB_PASSWORD'],
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix' => '',

            ]);
            $capsule->setAsGlobal();
            $capsule->bootEloquent();
            error_log("Conectado a la base de datos");
        } catch (\Exception $e) {
            die("Error al conectar a la base de datos: " . $e->getMessage());
        }
    }
}
