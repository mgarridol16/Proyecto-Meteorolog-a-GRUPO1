<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Datos extends Model{
    protected $table = "datos";

    protected $primaryKey = 'fechaSistema';

    protected $keyType = 'string';    // Le dice que la clave no es un número
    public $incrementing = false;     // Le dice que no intente sumarle +1

    public $timestamps = false;

    protected $fillable = ["temperatura", "presion", "humedad", "viento", "lluvia"];
}



