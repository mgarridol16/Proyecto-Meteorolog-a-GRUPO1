<?php
namespace App\Models;
use Illuminate\Database\Capsule\Manager as Capsule;
class Database{
    public function __construct($hostname, $port, $dbname, $dbuser, $dbpassword){
        try {
            $capsule = new Capsule;

            $capsule->addConnection([
                'driver'    => 'mysql',
                'host'      => $hostname,
                'port'      => $port,
                'database'  => $dbname,
                'username'  => $dbuser,
                'password'  => $dbpassword,
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
