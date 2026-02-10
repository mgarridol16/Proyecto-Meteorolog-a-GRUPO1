<?php
namespace App\Routes;
class Router
{
    private $rutas = [];
    public function __construct()
    {

        #$this->rutas['/'] = ['controller' => 'Controller', 'action' => 'index'];
        $this->rutas['/testdb'] = ['controller' => 'Controller','action' => 'testDB'];


        //Rutas Victor
        //$this->rutas['/'] = ['controller' => 'Controller_victor_vistas', 'action' => 'index'];
        $this->rutas['/about'] = ['controller' => 'Controller_victor_about', 'action' => 'about'];
        $this->rutas['/datos'] = ['controller' => 'Controller_victor_datos', 'action' => 'datos'];
        $this->rutas['/humedad'] = ['controller' => 'Controller_victor_humedad', 'action' => 'pedirHumedad'];

        //Rutas Miguel
        $this->rutas['/temperatura'] = ['controller' => 'Controller_miguel_temperatura', 'action' => 'historicoTemperatura'];

        //Rutas Miguel LLUVIA ACOMULADA
        $this->rutas['/lluviaAcomulada'] = ['controller' => 'Controller_miguel_lluviaAcomulada', 'action' => 'historicoLluviaAcomulada'];

        $this->rutas['/viento'] = ['controller' => 'Controller_miguel_viento', 'action' => 'historicoViento'];




        $this->rutas['/humedad'] = ['controller' => 'Controller_victor_humedad','action' => 'humedad'];
        $this->rutas['/filtrarHumedad'] = ['controller' => 'Controller_victor_humedad','action' => 'filtrarHumedad'];



        //Rutas Mikel
        $this->rutas['/medidas-24h'] = ['controller' => 'Controller_mikel', 'action' => 'medidas'];
        $this->rutas['/'] = ['controller' => 'Controller_mikel', 'action' => 'medidas'];
        $this->rutas['/historico-viento'] = ['controller' => 'Controller_mikel', 'action' => 'historicoViento'];


        $this->rutas['/filtrarHumedad'] = ['controller' => 'Controller_victor_humedad','action' => 'filtrarHumedad'];

        //Rutas Miguel
        // $this->rutas['/temperatura'] = ['controller' => 'Controller_miguel_temperatura','action' => 'historicoTemperatura'];

        //rutas luismi
        $this->rutas['/temperatura'] = ['controller' => 'Controller_Luismi', 'action' => 'historicoTemperatura'];
        $this->rutas['/presion'] = ['controller' => 'Controller_Luismi', 'action' => 'historicoPresion'];
    }

    public function handleRequest()
    {
        error_log($_SERVER["REQUEST_URI"]);
        $ruta = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (isset($this->rutas[$ruta])) {
            $route = $this->rutas[$ruta];
            $controllerClass = "App\\Controllers\\" . $route['controller'];
            $action = $route['action'];

            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                if (method_exists($controller, $action)) {
                    $controller->$action($_REQUEST);
                } else {
                    http_response_code(404);
                    echo "404 - Acción no encontrada";
                }
            } else {
                http_response_code(404);
                echo "404 - Controlador no encontrado";
            }
        } else {
            http_response_code(404);
            echo "404 - Página no encontrada";
        }
    }
}
$router = new Router();
$router->handleRequest();
