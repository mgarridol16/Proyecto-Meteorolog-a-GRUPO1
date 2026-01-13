<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as Capsule;

class Modelo_temperatura extends Model
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

  public static function obtenerTemperaturas30Dias()
  {
    $fechaLimite = date('Y-m-d H:i:s', strtotime('-30 days'));
    return self::where('fechaSistema', '>=', $fechaLimite)->orderBy('fechaSistema', 'asc')->get();
  }

  public static function obtenerEstadisticas($inicio, $fin)
  {
    return self::where('fechaSistema', '>=', $inicio)
      ->where('fechaSistema', '<=', $fin)
      ->selectRaw('
                    AVG(temperatura) as temperatura_media,
                    MIN(temperatura) as temperatura_minima,
                    MAX(temperatura) as temperatura_maxima,
                    AVG(humedad) as humedad_media,
                    MIN(humedad) as humedad_minima,
                    MAX(humedad) as humedad_maxima,
                    AVG(presion) as presion_media,
                    MIN(presion) as presion_minima,
                    MAX(presion) as presion_maxima,
                    AVG(viento) as viento_medio,
                    MIN(viento) as viento_minimo,
                    MAX(viento) as viento_maximo,
                    SUM(lluvia) as lluvia_total
                ')
      ->first();
  }

  public static function listarConLimite($limite)
  {
    return self::orderBy('fechaSistema', 'desc')->limit($limite)->get();
  }
  // Para la semana actual (sin filtro)
  public static function obtenerTemperaturasSemana()
  {
    $inicioSemana = date('Y-m-d 00:00:00', strtotime('monday this week'));
    $finSemana = date('Y-m-d 23:59:59', strtotime('sunday this week'));

    return self::obtenerTemperaturasPorDia($inicioSemana, $finSemana);
  }

  // Para cualquier rango de fechas (reutilizable)
  public static function obtenerTemperaturasPorDia($fechaInicio, $fechaFin)
  {
    // 1. Obtener datos reales de la BD (método simple del tema, pág. 80-82)
    $datosReales = self::where('fechaSistema', '>=', $fechaInicio)
      ->where('fechaSistema', '<=', $fechaFin)
      ->get();

    // 2. Agrupar manualmente por fecha (usando arrays básicos, pág. 14-15)
    $datosPorFecha = [];
    foreach ($datosReales as $registro) {
      $fecha = date('Y-m-d', strtotime($registro->fechaSistema));

      if (!isset($datosPorFecha[$fecha])) {
        $datosPorFecha[$fecha] = [
          'temperaturas' => [],
          'maxima' => $registro->temperatura,
          'minima' => $registro->temperatura
        ];
      }

      $datosPorFecha[$fecha]['temperaturas'][] = $registro->temperatura;

      // Actualizar máxima y mínima
      if ($registro->temperatura > $datosPorFecha[$fecha]['maxima']) {
        $datosPorFecha[$fecha]['maxima'] = $registro->temperatura;
      }
      if ($registro->temperatura < $datosPorFecha[$fecha]['minima']) {
        $datosPorFecha[$fecha]['minima'] = $registro->temperatura;
      }
    }

    // 3. Generar todos los días del rango (pág. 19 - funciones de fecha)
    $inicio = strtotime($fechaInicio);
    $fin = strtotime($fechaFin);
    $resultado = [];

    for ($fecha = $inicio; $fecha <= $fin; $fecha = strtotime('+1 day', $fecha)) {
      $fechaStr = date('Y-m-d', $fecha);
      $diaSemana = date('l', $fecha); // Monday, Tuesday...

      // Si hay datos para este día, calcular media
      if (isset($datosPorFecha[$fechaStr])) {
        $temps = $datosPorFecha[$fechaStr]['temperaturas'];
        $media = array_sum($temps) / count($temps); // array_sum y count, pág. 19

        $resultado[] = [
          'fecha' => $fechaStr,
          'dia_semana' => self::traducirDia($diaSemana),
          'temperatura_media' => round($media, 1),
          'temperatura_maxima' => $datosPorFecha[$fechaStr]['maxima'],
          'temperatura_minima' => $datosPorFecha[$fechaStr]['minima'],
          'num_lecturas' => count($temps),
          'tiene_datos' => true
        ];
      } else {
        // Día sin datos
        $resultado[] = [
          'fecha' => $fechaStr,
          'dia_semana' => self::traducirDia($diaSemana),
          'temperatura_media' => null,
          'temperatura_maxima' => null,
          'temperatura_minima' => null,
          'num_lecturas' => 0,
          'tiene_datos' => false
        ];
      }
    }

    return $resultado;
  }

  // Método auxiliar (pág. 22 - funciones)
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
}
