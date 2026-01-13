<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as Capsule;

class Modelo_temperatura extends Model
{
  protected $table = "datos";
  protected $primaryKey = 'fechaSistema';

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
}
