<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Modelo_Presion extends Model
{
    protected $table = "datos";
    protected $primaryKey = 'fechaSistema';
    public $timestamps = false;
    protected $fillable = [
        "fechaSistema",
        "temperatura",
        "humedad",
        "presion",
        "viento",
        "lluvia"
    ];

    public static function obtenerTodasPresiones(){
        return self::orderBy('fechaSistema', 'DESC')->get();
    }

    public static function obtenerPresiones30Dias(){
        return self::selectRaw("DATE_FORMAT(fechaSistema, '%Y-%m-%d %H:%i') as fecha, ROUND(presion, 2) as presion")
            ->whereRaw('fechaSistema >= DATE_SUB(NOW(), INTERVAL 30 DAY)')
            ->orderBy('fechaSistema', 'ASC')
            ->get()
            ->toArray();
    }

    public static function obtenerUltimaPresion(){
        return self::orderBy('fechaSistema', 'DESC')
            ->first();
    }
}