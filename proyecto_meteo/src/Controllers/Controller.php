<?php

namespace App\Controllers;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use App\Models\Database;
use Dotenv\Dotenv;

class Controller
{
    private $dotenv;
    private Environment $twig;
    private $model;

    public function __construct()
    {
        $dotenv= Dotenv::createImmutable(__DIR__ . "/../..");
        $dotenv->load();
        $hostname= $_ENV['DB_HOST'];
        $port=$_ENV['DB_PORT'];
        $dbname=$_ENV['DB_DATABASE'];
        $dbuser=$_ENV['DB_USERNAME'];
        $dbpassword=$_ENV['DB_PASSWORD'];
        $key=$_ENV['KEY'];
        
        $this->model = new Database();

    }

    public function index()
    {
        echo "prueba";
    }
    public function testDB()
{
    try {
        \Illuminate\Database\Capsule\Manager::connection()->getPdo();
        echo " Conexión correcta a la base de datos.";
    } catch (\Exception $e) {
        echo " Error al conectar: " . $e->getMessage();
    }
}

}
