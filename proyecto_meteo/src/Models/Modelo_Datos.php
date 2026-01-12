<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;
class Modelo_Datos extends Model{
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

    public static function obtenerUltimaTemperatura(){
        $registro = self::orderBy('fechaSistema', 'DESC')->first();
        if ($registro) {
            return $registro;
        }
        return null;
    }

    public static function obtenerTemperaturas30Dias(){
        $sql = "SELECT DATE_FORMAT(fechaSistema, '%Y-%m-%d %H:%i') as fecha, 
                    ROUND(temperatura, 2) as temperatura
                FROM datos 
                WHERE fechaSistema >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                ORDER BY fechaSistema ASC";
        return DB::connection()->select($sql);
    }

    public static function obtenerUltimaPresion(){
        $registro = self::orderBy('fechaSistema', 'DESC')->first();
        if ($registro) {
            return $registro;
        }
        return null;
    }

    public static function obtenerPresiones30Dias(){
        $sql = "SELECT DATE_FORMAT(fechaSistema, '%Y-%m-%d %H:%i') as fecha, 
                    ROUND(presion, 2) as presion
                FROM datos 
                WHERE fechaSistema >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                ORDER BY fechaSistema ASC";
        return DB::connection()->select($sql);
    }
}