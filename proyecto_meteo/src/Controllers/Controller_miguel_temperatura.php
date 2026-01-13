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
    $fechaInicio = $request['fechaInicio'] ?? null;
    $fechaFin = $request['fechaFin'] ?? null;

    if ($fechaInicio && $fechaFin) {
      $temperaturaEntreFechas = Modelo_temperatura::buscarEntreFechas($fechaInicio, $fechaFin);
      $estadisticasEntreFechas = Modelo_temperatura::obtenerEstadisticas($fechaInicio, $fechaFin);
    }

    $limiteFecha = $request['limiteFecha'] ?? null;
    if ($limiteFecha) {
      $temperaturaHastaFecha = Modelo_temperatura::listarConLimite($limiteFecha);
    }

    $fechaBuscada = $request['fechaBuscada'] ?? null;
    if ($fechaBuscada) {
      $temperaturaPorFecha = Modelo_temperatura::buscarPorFecha($fechaBuscada);
    }

    $todasLasTemperaturas = Modelo_temperatura::listarTodo();
    $ultimaTemperatura = Modelo_temperatura::obtenerUltima();
    $temperaturas30Dias = Modelo_temperatura::obtenerTemperaturas30Dias();

    echo $this->twig->render('miguel_temperatura.html.twig', [
      'temperaturaEntreFechas' => $temperaturaEntreFechas ?? null,
      'estadisticasEntreFechas' => $estadisticasEntreFechas ?? null,
      'temperaturaHastaFecha' => $temperaturaHastaFecha ?? null,
      'temperaturaPorFecha' => $temperaturaPorFecha ?? null,
      'todasLasTemperaturas' => $todasLasTemperaturas,
      'ultimaTemperatura' => $ultimaTemperatura,
      'temperaturas30Dias' => $temperaturas30Dias
    ]);
  }
}
