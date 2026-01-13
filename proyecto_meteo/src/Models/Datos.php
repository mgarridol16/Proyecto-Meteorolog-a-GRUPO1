<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Datos extends Model{
    protected $table = "datos";

    // protected $primaryKey = 'id';

    protected $keyType = 'string';    // Le dice que la clave no es un número
    public $incrementing = false;     

    public $timestamps = false;

    protected $fillable = ["temperatura", "presion", "humedad", "viento", "lluvia"];
}



