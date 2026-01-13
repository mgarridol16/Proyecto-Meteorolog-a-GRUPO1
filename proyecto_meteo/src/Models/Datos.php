<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Datos extends Model{
    protected $table = "datos";

    // protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = ["temperatura", "presion", "humedad", "viento", "lluvia"];
}