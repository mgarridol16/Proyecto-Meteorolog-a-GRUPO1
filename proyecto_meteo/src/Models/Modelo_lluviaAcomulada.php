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

  public function listarTodasLLuvias(){
    return self::all();
  }

  

}
