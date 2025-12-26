<?php

namespace App\Controllers;


use Dotenv\Dotenv;
use App\Models\Datos;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

use App\Models\Database;
use App\Controllers\now;

class Controller_mikel
{
    private $dotenv;
    private $twig;
    private $model;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . "/../..");
        $dotenv->load();
        $hostname = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $dbname = $_ENV['DB_DATABASE'];
        $dbuser = $_ENV['DB_USERNAME'];
        $dbpassword = $_ENV['DB_PASSWORD'];
        $key = $_ENV['KEY'];

        $loader = new FilesystemLoader(__DIR__ . "/../Views");
        $this->twig = new Environment($loader);

        $this->model = new Database($hostname, $port, $dbname, $dbuser, $dbpassword);
    }

    
    public function getDatosUltimas24Horas()
    {
        $fecha24HorasAtras = date('Y-m-d H:i:s', time() - 86400);
        return Datos::where('fechaSistema', '>=', $fecha24HorasAtras)
                    ->orderBy('fechaSistema', 'desc')
                    ->get();
    }

    public function getEstadisticas24Horas()
    {

    {
        $fecha24HorasAtras = date('Y-m-d H:i:s', time() - 86400);
        return Datos::where('fechaSistema', '>=', $fecha24HorasAtras)
                    ->selectRaw('COUNT(*) as total_registros')
                    ->selectRaw('AVG(temperatura) as temp_promedio')
                    ->selectRaw('MIN(temperatura) as temp_minima')
                    ->selectRaw('MAX(temperatura) as temp_maxima')
                    ->selectRaw('AVG(humedad) as humedad_promedio')
                    ->selectRaw('AVG(presion) as presion_promedio')
                    ->selectRaw('AVG(viento) as viento_promedio')
                    ->selectRaw('SUM(lluvia) as lluvia_total')
                    ->first();
    }
    }

    public function medidas()
    {
        try {
            $datos24h = $this->getDatosUltimas24Horas();
            $estadisticas = $this->getEstadisticas24Horas();
            
            echo $this->twig->render('medidas_24h.html.twig', [
                'datos' => $datos24h,
                'estadisticas' => $estadisticas,
                'titulo' => 'Medidas Meteorológicas - Últimas 24 Horas'
            ]);
        } catch (\Exception $e) {
            echo "Error al cargar los datos: " . $e->getMessage();
        }
    }

}