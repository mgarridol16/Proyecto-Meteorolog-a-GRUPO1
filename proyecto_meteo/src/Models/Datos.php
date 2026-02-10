<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

use function Symfony\Component\Clock\now;

class Datos extends Model{
    protected $table = "datos";
    public $timestamps = false;
    protected $fillable = ["temperatura", "presion", "humedad", "viento", "lluvia"];


    public static function obtenerUltimaTemperatura(){
        return self::orderBy('fechaSistema', 'DESC')->first();
    }

    public static function obtenerTemperaturas30Dias(){
        return self::where('fechaSistema', '>=', date('Y-m-d H:i:s', strtotime('-30 days')))
                ->orderBy('fechaSistema', 'ASC')
                ->get(['fechaSistema', 'temperatura']);
    }

    public static function obtenerTemperaturasTabla($fechaDesde = null, $fechaHasta = null){
        $query = self::orderBy('fechaSistema', 'DESC');
        if ($fechaDesde) {
            $query->where('fechaSistema', '>=', $fechaDesde);
        }
        if ($fechaHasta) {
            $query->where('fechaSistema', '<=', $fechaHasta);
        }
        return $query->limit(10)->get();
    }

    public static function obtenerUltimaPresion(){
        return self::orderBy('fechaSistema', 'DESC')->first();
    }

    public static function obtenerPresiones30Dias(){

        return self::where('fechaSistema', '>=', date('Y-m-d H:i:s', strtotime('-30 days')))
                ->orderBy('fechaSistema', 'ASC')
                ->get(['fechaSistema', 'presion']);
    }
    
    public static function obtenerPresionesTabla($fechaDesde = null, $fechaHasta = null){
        $query = self::orderBy('fechaSistema', 'DESC');
        if ($fechaDesde) {
            $query->where('fechaSistema', '>=', $fechaDesde);
        }
        if ($fechaHasta) {
            $query->where('fechaSistema', '<=', $fechaHasta);
        }
        return $query->limit(10)->get();
    }
}

