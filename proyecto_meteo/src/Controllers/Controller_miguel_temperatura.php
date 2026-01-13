<?php

namespace App\Controllers;


use Dotenv\Dotenv;
use App\Models\Datos;
use App\Models\Temperatura;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

use App\Models\Database;
use App\Models\Modelo_temperatura;

class Controller_miguel_temperatura
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
    $ultimaTemperatura = Modelo_temperatura::obtenerUltima();

    $fechaInicio = $request['fechaInicio'] ?? null;
    $fechaFin = $request['fechaFin'] ?? null;

    if ($fechaInicio && $fechaFin) {
      // Cuando hay filtro: mostrar temperaturas del rango filtrado
      $registros = Modelo_temperatura::buscarEntreFechas($fechaInicio, $fechaFin);
      $stats = Modelo_temperatura::obtenerEstadisticas($fechaInicio, $fechaFin);
      $temperaturasSemana = Modelo_temperatura::obtenerTemperaturasPorDia($fechaInicio, $fechaFin);
    } else {
      // Sin filtro: mostrar últimos 30 días y semana actual
      $registros = Modelo_temperatura::obtenerTemperaturas30Dias();
      $stats = null;
      $temperaturasSemana = Modelo_temperatura::obtenerTemperaturasSemana(); // Semana actual
    }

    echo $this->twig->render('miguel_temperatura.html.twig', [
      'todasLasTemperaturas' => $registros,
      'temperaturas30Dias' => $registros,
      'ultimaTemperatura' => $ultimaTemperatura,
      'temperaturasSemana' => $temperaturasSemana,
      'estadisticasEntreFechas' => $stats,
      'fechaInicio' => $fechaInicio,
      'fechaFin' => $fechaFin
    ]);
  }
}
