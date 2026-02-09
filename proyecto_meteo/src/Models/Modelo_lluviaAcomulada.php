<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as Capsule;

class Modelo_lluviaAcomulada extends Model
{
  protected $table = "datos";
  protected $primaryKey = 'fechaSistema';
  protected $keyType = 'string';
  public $timestamps = false;
  public $incrementing = false;
  protected $fillable = [
    "fechaSistema",
    "temperatura",
    "humedad",
    "presion",
    "viento",
    "lluvia"
  ];

  public static function listarTodo()
  {
    return self::all();
  }

  public static function buscarEntreFechas($inicio, $fin)
  {
    return self::where('fechaSistema', '>=', $inicio)->where('fechaSistema', '<=', $fin)->get();
  }

  public static function buscarPorFecha($fecha)
  {
    return self::where('fechaSistema', $fecha)->first();
  }

  public static function obtenerUltima()
  {
    return self::orderBy('fechaSistema', 'desc')->first();
  }

  public static function obtenerLluvia30Dias()
  {
    $fechaLimite = date('Y-m-d H:i:s', strtotime('-30 days'));
    return self::where('fechaSistema', '>=', $fechaLimite)->orderBy('fechaSistema', 'asc')->get();
  }

  public static function obtenerEstadisticas($inicio, $fin)
  {
    return self::where('fechaSistema', '>=', $inicio)
      ->where('fechaSistema', '<=', $fin)
      ->selectRaw('
                    SUM(lluvia) as lluvia_acumulada,
                    AVG(lluvia) as lluvia_media,
                    MIN(lluvia) as lluvia_minima,
                    MAX(lluvia) as lluvia_maxima,
                    COUNT(lluvia) as registros_lluvia
                ')
      ->first();
  }

  public static function listarConLimite($limite)
  {
    return self::orderBy('fechaSistema', 'desc')->limit($limite)->get();
  }

  public static function obtenerLluviaSemana()
  {
    $inicioSemana = date('Y-m-d 00:00:00', strtotime('monday this week'));
    $finSemana = date('Y-m-d 23:59:59', strtotime('sunday this week'));

    return self::obtenerLluviaPorDia($inicioSemana, $finSemana);
  }

  public static function obtenerLluviaPorDia($fechaInicio, $fechaFin)
  {

    $datosReales = self::where('fechaSistema', '>=', $fechaInicio)
      ->where('fechaSistema', '<=', $fechaFin)
      ->get();

    $datosPorFecha = [];
    foreach ($datosReales as $registro) {
      $fecha = date('Y-m-d', strtotime($registro->fechaSistema));

      if (!isset($datosPorFecha[$fecha])) {
        $datosPorFecha[$fecha] = [
          'lluvias' => [],
          'maxima' => $registro->lluvia,
          'minima' => $registro->lluvia
        ];
      }

      $datosPorFecha[$fecha]['lluvias'][] = $registro->lluvia;

      if ($registro->lluvia > $datosPorFecha[$fecha]['maxima']) {
        $datosPorFecha[$fecha]['maxima'] = $registro->lluvia;
      }
      if ($registro->lluvia < $datosPorFecha[$fecha]['minima']) {
        $datosPorFecha[$fecha]['minima'] = $registro->lluvia;
      }
    }

    $inicio = strtotime($fechaInicio);
    $fin = strtotime($fechaFin);
    $resultado = [];

    for ($fecha = $inicio; $fecha <= $fin; $fecha = strtotime('+1 day', $fecha)) {
      $fechaStr = date('Y-m-d', $fecha);
      $diaSemana = date('l', $fecha);

      if (isset($datosPorFecha[$fechaStr])) {
        $lluvias = $datosPorFecha[$fechaStr]['lluvias'];
        $acumulada = array_sum($lluvias);

        $resultado[] = [
          'fecha' => $fechaStr,
          'dia_semana' => self::traducirDia($diaSemana),
          'lluvia_acumulada' => round($acumulada, 2),
          'lluvia_maxima' => $datosPorFecha[$fechaStr]['maxima'],
          'lluvia_minima' => $datosPorFecha[$fechaStr]['minima'],
          'num_lecturas' => count($lluvias),
          'tiene_datos' => true
        ];
      } else {
        $resultado[] = [
          'fecha' => $fechaStr,
          'dia_semana' => self::traducirDia($diaSemana),
          'lluvia_acumulada' => null,
          'lluvia_maxima' => null,
          'lluvia_minima' => null,
          'num_lecturas' => 0,
          'tiene_datos' => false
        ];
      }
    }

    return $resultado;
  }


  private static function traducirDia($diaIngles)
  {
    $dias = [
      'Monday' => 'Lunes',
      'Tuesday' => 'Martes',
      'Wednesday' => 'Miércoles',
      'Thursday' => 'Jueves',
      'Friday' => 'Viernes',
      'Saturday' => 'Sábado',
      'Sunday' => 'Domingo'
    ];

    return $dias[$diaIngles] ?? $diaIngles;
  }

  // FUNCIONES PACK LLUVIA ACUMULADA - MIGUEL
  public static function buscarLluviaEntreFechas($inicio, $fin)
  {
    // Limpiamos la 'T' del formato HTML para SQL
    $f_i = str_replace('T', ' ', $inicio);
    $f_f = str_replace('T', ' ', $fin);

    return self::whereBetween('fechaSistema', [$f_i, $f_f])
      ->select('fechaSistema', 'lluvia', 'humedad')
      ->orderBy('fechaSistema', 'asc')
      ->get();
  }

  public static function obtenerEstadisticasLluvia($inicio, $fin)
  {
    $f_i = str_replace('T', ' ', $inicio);
    $f_f = str_replace('T', ' ', $fin);

    return self::whereBetween('fechaSistema', [$f_i, $f_f])
      ->selectRaw('SUM(lluvia) as lluvia_acumulada,
                     AVG(lluvia) as lluvia_media,
                     MIN(lluvia) as lluvia_minima,
                     MAX(lluvia) as lluvia_maxima')
      ->first();
  }

  public static function obtenerUltimaLluvia()
  {
    return self::orderBy('fechaSistema', 'desc')->first();
  }

  public static function listarLluviaConLimite($n)
  {
    return self::orderBy('fechaSistema', 'desc')
      ->limit($n)
      ->get()
      ->reverse();
  }

  public static function buscarLuviaPorFecha($fecha)
  {
    return self::where('fechaSistema', 'like', $fecha . '%')->get();
  }
}
