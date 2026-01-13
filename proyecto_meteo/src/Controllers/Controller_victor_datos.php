<?php

namespace App\Controllers;


use Dotenv\Dotenv;
use App\Models\Datos;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

use App\Models\Database;

class Controller_victor_datos
{
    private $dotenv;
    private $twig;
    private $model;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . "/../..");
        $dotenv->load();
        $hostname = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $dbname = $_ENV['DB_DATABASE'];
        $dbuser = $_ENV['DB_USERNAME'];
        $dbpassword = $_ENV['DB_PASSWORD'];
        $key = $_ENV['KEY'];

        $loader = new FilesystemLoader(__DIR__ . "/../Views");
        $this->twig = new Environment($loader);

        $this->model = new Database($hostname, $port, $dbname, $dbuser, $dbpassword);
    }

    public function datos($get_info)
    {

        $temperatura = $get_info["temp"] ?? NULL;
        $presion = $get_info["pres"] ?? NULL;
        $humedad = $get_info["hum"] ?? NULL;
        $viento = $get_info["viento"] ?? NULL;
        $lluvia = $get_info["lluvia"] ?? NULL;

        try {
            Datos::create([
                "temperatura" => $temperatura,
                "presion" => $presion,
                "humedad" => $humedad,
                "viento" => $viento,
                "lluvia" => $lluvia,
            ]); 
            
            error_log("Entrada grabada con exito");
            http_response_code(201);
            echo json_encode(["mensaje" => "Entrada insertada correctamente"]);
        }catch (\Exception $e){
            die("Entrada erronea: "  . $e -> getMessage());
        }

    }


}
