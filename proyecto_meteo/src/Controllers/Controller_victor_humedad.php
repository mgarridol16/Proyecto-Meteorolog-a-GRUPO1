<?php

namespace App\Controllers;


use Dotenv\Dotenv;
use App\Models\Datos;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

use App\Models\Database;

class Controller_victor_humedad
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

    public function about()
    {
        echo $this->twig->render("about.html.twig");
    }

    public function pedirHumedad()
    {
        $datos = Datos::all("fechaSistema", "humedad");
        return $datos;
    }

    public function pedirHumedad30Dias()
    {
        $datos = $this->pedirHumedad();
        $datosUltimos30Dias = [];
        $fechaInicio = (new \DateTime())->modify('-30 days');
        $fechaActual = new \DateTime();

        foreach ($datos as $dato) {
            $fechaDato = new \DateTime($dato->fechaSistema);
            if ($fechaDato >= $fechaInicio && $fechaDato <= $fechaActual) {
                $datosUltimos30Dias[] = $dato;
            }
        }
        return $datosUltimos30Dias;
    }

    public function filtrarHumedad($request)
    {
        $fechaInicio = $request['fechaDesde'] ?? null;
        $fechaFin = $request['fechaHasta'] ?? null;
        $datos = $this->pedirHumedad();
        $datosFiltrados = [];

        if ($fechaInicio && $fechaFin) {
            $fechaInicioObj = new \DateTime($fechaInicio);
            $fechaFinObj = new \DateTime($fechaFin);

            foreach ($datos as $dato) {
                $fechaDato = new \DateTime($dato->fechaSistema);
                if ($fechaDato >= $fechaInicioObj && $fechaDato <= $fechaFinObj) {
                    $datosFiltrados[] = $dato;
                }
            }
        }

        echo $this->twig->render("humedad.html.twig", [
            "datos" => $datosFiltrados,
            "fechaInicio" => $fechaInicio,
            "fechaFin" => $fechaFin
        ]);

    }

    public function humedad()
    {
        $datos = $this->pedirHumedad30Dias();

        echo $this->twig->render("humedad.html.twig", [
            "datos" => $datos
        ]);
    }
}