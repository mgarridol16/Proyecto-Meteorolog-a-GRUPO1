<?php

namespace App\Controllers;

use Dotenv\Dotenv;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use App\Models\Database;
use App\Models\Modelo_lluviaAcomulada;

class Controller_miguel_lluviaAcomulada
{
  private $twig;

  public function __construct()
  {
    $dotenv = Dotenv::createImmutable(__DIR__ . "/../..");
    $dotenv->load();

    // Inicializamos la base de datos como en clase
    new Database(
      $_ENV['DB_HOST'],
      $_ENV['DB_PORT'],
      $_ENV['DB_DATABASE'],
      $_ENV['DB_USERNAME'],
      $_ENV['DB_PASSWORD']
    );

    $loader = new FilesystemLoader(__DIR__ . "/../Views");
    $this->twig = new Environment($loader);
  }


  public function historicoLluviaAcomulada($request)
  {
    $ultimaLluvia = Modelo_lluviaAcomulada::obtenerUltima();

    $fechaInicio = $request['fechaInicio'] ?? null;
    $fechaFin = $request['fechaFin'] ?? null;

    if ($fechaInicio && $fechaFin) {
      $registros = Modelo_lluviaAcomulada::buscarLluviaEntreFechas($fechaInicio, $fechaFin);
      $stats = Modelo_lluviaAcomulada::obtenerEstadisticasLluvia($fechaInicio, $fechaFin);
      $lluviaSemana = Modelo_lluviaAcomulada::obtenerLluviaPorDia($fechaInicio, $fechaFin);
    } else {
      $registros = Modelo_lluviaAcomulada::obtenerLluvia30Dias();
      $stats = null;
      $lluviaSemana = Modelo_lluviaAcomulada::obtenerLluviaSemana();
    }


    $diasAbreviados = [
      'Lunes' => 'Lun',
      'Martes' => 'Mar',
      'Miércoles' => 'Mié',
      'Jueves' => 'Jue',
      'Viernes' => 'Vie',
      'Sábado' => 'Sáb',
      'Domingo' => 'Dom'
    ];

    foreach ($lluviaSemana as &$dia) {
      $dia['dia_semana_es'] = $diasAbreviados[$dia['dia_semana']] ?? $dia['dia_semana'];
    }

    echo $this->twig->render('miguel_lluviaAcomulada.html.twig', [
      'todasLasLluvias' => $registros,
      'lluvias30Dias' => $registros,
      'ultimaLluvia' => $ultimaLluvia,
      'lluviaSemana' => $lluviaSemana,
      'estadisticasEntreFechas' => $stats,
      'fechaInicio' => $fechaInicio,
      'fechaFin' => $fechaFin
    ]);
  }
}
