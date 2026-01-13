<?php
namespace App\Controllers;
use Dotenv\Dotenv;
use App\Models\Modelo_Datos;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use App\Models\Database;
class Controller_Luismi{
    private $dotenv;
    private $twig;
    private $model;
    private $modeloDatos;

    public function __construct(){
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
        $this->modeloDatos = new Modelo_Datos();
    }

    public function historicoTemperatura($request){
        $ultimaTemp = Modelo_Datos::obtenerUltimaTemperatura();
        $temperaturas = Modelo_Datos::obtenerTemperaturas30Dias();
        $fechaDesde = $_GET['fecha_desde'] ?? null;
        $fechaHasta = $_GET['fecha_hasta'] ?? null;
        $tablaDatos = Modelo_Datos::obtenerTemperaturasTabla($fechaDesde, $fechaHasta);
        if ($ultimaTemp) {
            $ultimaTemp = $ultimaTemp->toArray();
        }
        
        echo $this->twig->render('temperatura_historico.html.twig', [
            'titulo' => 'Histórico de Temperatura - Últimos 30 días',
            'temperaturas' => $temperaturas,
            'ultimaTemp' => $ultimaTemp,
            'tablaDatos' => $tablaDatos,
            'fechaDesde' => $fechaDesde,
            'fechaHasta' => $fechaHasta
        ]);
    }

    public function historicoPresion($request){
        $ultimaPresion = Modelo_Datos::obtenerUltimaPresion();
        $presiones = Modelo_Datos::obtenerPresiones30Dias();
        $fechaDesde = $_GET['fecha_desde'] ?? null;
        $fechaHasta = $_GET['fecha_hasta'] ?? null;
        $tablaDatos = Modelo_Datos::obtenerPresionesTabla($fechaDesde, $fechaHasta);
        if ($ultimaPresion) {
            $ultimaPresion = $ultimaPresion->toArray();
        }
        
        echo $this->twig->render('presion_historico.html.twig', [
            'titulo' => 'Histórico de Presión Atmosférica - Últimos 30 días',
            'presiones' => $presiones,
            'ultimaPresion' => $ultimaPresion,
            'tablaDatos' => $tablaDatos,
            'fechaDesde' => $fechaDesde,
            'fechaHasta' => $fechaHasta
        ]);
    }
}