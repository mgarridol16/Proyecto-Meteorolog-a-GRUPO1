<?php

namespace App\Controllers;

use Dotenv\Dotenv;
use App\Models\Datos;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use App\Models\Database;

class Controller_mikel
{
    private $twig;

    public function __construct()
    {
        // 1. Carga de variables de entorno
        $dotenv = Dotenv::createImmutable(__DIR__ . "/../..");
        $dotenv->load();
        
        // 2. Inicialización de la base de datos (Eloquent lo usa internamente)
        new Database(
            $_ENV['DB_HOST'], 
            $_ENV['DB_PORT'], 
            $_ENV['DB_DATABASE'], 
            $_ENV['DB_USERNAME'], 
            $_ENV['DB_PASSWORD']
        );

        // 3. Configuración de Twig
        $loader = new FilesystemLoader(__DIR__ . "/../Views");
        $this->twig = new Environment($loader);
    }

    /**
     * Obtiene los registros detallados filtrando por el rango de 24h.
     */
    private function getDatosRango24h($inicio, $fin)
    {
        Datos::first();
        return Datos::where('fechaSistema', '>=', $inicio)
                    ->where('fechaSistema', '<=', $fin)
                    ->orderBy('fechaSistema', 'desc')
                    ->get();
    }

    /**
     * Obtiene las estadísticas aplicando el MISMO filtro de tiempo que la tabla.
     */
    private function getEstadisticasRango24h($inicio, $fin)
    {
        return Datos::where('fechaSistema', '>=', $inicio)
                    ->where('fechaSistema', '<=', $fin)
                    ->selectRaw('AVG(temperatura) as temp_promedio')
                    ->selectRaw('MIN(temperatura) as temp_minima')
                    ->selectRaw('MAX(temperatura) as temp_maxima')
                    ->selectRaw('AVG(humedad) as humedad_promedio')
                    ->selectRaw('AVG(viento) as viento_promedio')
                    ->selectRaw('SUM(lluvia) as lluvia_total')
                    ->first();
    }

    /**
     * Función principal que coordina la carga de datos y la vista.
     */
    public function medidas()
    {
        try {
            // Definimos el rango una sola vez para ambas consultas
            $fin = date('Y-m-d H:i:s');
            $inicio = date('Y-m-d H:i:s', strtotime('-24 hours'));

            $datos24h = $this->getDatosRango24h($inicio, $fin);
            $estadisticas = $this->getEstadisticasRango24h($inicio, $fin);
            
            echo $this->twig->render('medidas_24h.html.twig', [
                'datos'        => $datos24h,
                'estadisticas' => $estadisticas,
                'titulo'       => 'Medidas Meteorológicas - Últimas 24 Horas'
            ]);

        } catch (\Exception $e) {
            echo "Error crítico en el controlador: " . $e->getMessage();
        }
    }

    // C9: Controlador para gestionar vistas de Velocidad del viento
    public function historicoViento()
    {
        // Captura de fechas para el filtro (M4.5)
        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('-7 days'));
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');

        // M4.5: Métodos de acceso a la columna 'viento'
        $datosViento = Datos::select('fechaSistema', 'viento')
                        ->whereBetween('fechaSistema', [$fechaInicio . " 00:00:00", $fechaFin . " 23:59:59"])
                        ->orderBy('fechaSistema', 'ASC')
                        ->get();

        echo $this->twig->render('historico_viento.html.twig', [
            'datos' => $datosViento,
            'titulo' => 'Análisis del Viento',
            'filtros' => ['inicio' => $fechaInicio, 'fin' => $fechaFin]
        ]);
    }
}