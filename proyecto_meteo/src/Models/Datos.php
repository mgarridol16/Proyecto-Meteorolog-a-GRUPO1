<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;
class Datos extends Model{
    protected $table = "datos";
    // protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ["temperatura", "presion", "humedad", "viento", "lluvia"];
    //metodos luismi
    public static function obtenerUltimaTemperatura(){
        return self::orderBy('fechaSistema', 'DESC')->first();
    }

    public static function obtenerTemperaturas30Dias(){
        $sql = "SELECT DATE_FORMAT(fechaSistema, '%Y-%m-%d %H:%i') as fecha, 
                    ROUND(temperatura, 2) as temperatura
                FROM datos 
                WHERE fechaSistema >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                ORDER BY fechaSistema ASC";
        return DB::connection()->select($sql);
    }

    public static function obtenerTemperaturasTabla($fechaDesde = null, $fechaHasta = null){
        $query = self::select('fechaSistema', 'temperatura', 'humedad', 'presion', 'viento', 'lluvia')
                    ->orderBy('fechaSistema', 'DESC');
        if ($fechaDesde) {
            $query->where('fechaSistema', '>=', $fechaDesde);
        }
        if ($fechaHasta) {
            $query->where('fechaSistema', '<=', $fechaHasta);
        }
        return $query->limit(20)->get();
    }

    public static function obtenerUltimaPresion(){
        return self::orderBy('fechaSistema', 'DESC')->first();
    }

    public static function obtenerPresiones30Dias(){
        $sql = "SELECT DATE_FORMAT(fechaSistema, '%Y-%m-%d %H:%i') as fecha, 
                    ROUND(presion, 2) as presion
                FROM datos 
                WHERE fechaSistema >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                ORDER BY fechaSistema ASC";
        return DB::connection()->select($sql);
    }

    public static function obtenerPresionesTabla($fechaDesde = null, $fechaHasta = null){
        $query = self::select('fechaSistema', 'temperatura', 'humedad', 'presion', 'viento', 'lluvia')
                    ->orderBy('fechaSistema', 'DESC');
        if ($fechaDesde) {
            $query->where('fechaSistema', '>=', $fechaDesde);
        }
        if ($fechaHasta) {
            $query->where('fechaSistema', '<=', $fechaHasta);
        }
        return $query->limit(20)->get();
    }
}