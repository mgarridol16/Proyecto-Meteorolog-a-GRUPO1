<?php

namespace App\Controllers;

use Dotenv\Dotenv;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use App\Models\Database;
use App\Models\Modelo_temperatura; // Usamos el mismo modelo donde añadimos el viento

class Controller_miguel_viento
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
  public function historicoViento($request)
  {
    $fechaInicio = $request['fechaInicio'] ?? null;
    $fechaFin = $request['fechaFin'] ?? null;

    if ($fechaInicio && $fechaFin) {
      $registros = Modelo_temperatura::buscarVientoEntreFechas($fechaInicio, $fechaFin);

      $referenciaParaSemana = $fechaInicio;
    } else {
      $registros = Modelo_temperatura::obtenerViento30Dias();
      if ($registros->isEmpty()) {
        $registros = Modelo_temperatura::listarVientoConLimite(50);
      }
      $stats = null;
      $ultimoDato = Modelo_temperatura::obtenerUltimoViento();
      $referenciaParaSemana = $ultimoDato->fechaSistema;
    }


    $vientoSemana = [];
    $diasEspanol = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];

    
    $lunesReferencia = date('Y-m-d', strtotime("monday this week", strtotime($referenciaParaSemana)));

    for ($i = 0; $i < 7; $i++) {
      $fechaDia = date('Y-m-d', strtotime("$lunesReferencia +$i days"));
      $statsDia = Modelo_temperatura::obtenerEstadisticasViento($fechaDia . ' 00:00:00', $fechaDia . ' 23:59:59');

      $vientoSemana[] = [
        'dia_semana_es' => $diasEspanol[$i],
        'fecha' => $fechaDia,
        'tiene_datos' => ($statsDia && $statsDia->viento_maximo !== null),
        'viento_media' => $statsDia->viento_medio ?? 0,
        'viento_maxima' => $statsDia->viento_maximo ?? 0,
        'viento_minima' => $statsDia->viento_minimo ?? 0
      ];
    }

    echo $this->twig->render('miguel_viento.html.twig', [
      'vientoSemana' => $vientoSemana,
      'registrosViento' => $registros,
      'estadisticas' => $stats,
      'actual' => Modelo_temperatura::obtenerUltimoViento(),
      'fechaInicio' => $fechaInicio,
      'fechaFin' => $fechaFin
    ]);
  }
}
