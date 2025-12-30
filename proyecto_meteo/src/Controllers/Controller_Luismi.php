<?php
namespace App\Controllers;
use Dotenv\Dotenv;
use App\Models\Modelo_Temperatura;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use App\Models\Database;

class Controller_Luismi
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


    public function historicoTemperatura($request)
    {
        try {
            $datosGrafico = Modelo_Temperatura::obtenerTemperaturas30Dias();
            $ultimaLectura = Modelo_Temperatura::obtenerUltimaTemperatura();
            $ultimaTemp = $ultimaLectura ? $ultimaLectura->toArray() : null;

            echo $this->twig->render('temperatura_historico.html.twig', [
                'titulo' => 'Histórico de Temperatura - Últimos 30 días',
                'temperaturas' => $datosGrafico,
                'ultimaTemp' => $ultimaTemp
            ]);

        } catch (\Exception $e) {
            error_log("Error en historicoTemperatura: " . $e->getMessage());
            echo "Error al cargar los datos de temperatura: " . $e->getMessage();
        }
    }
}