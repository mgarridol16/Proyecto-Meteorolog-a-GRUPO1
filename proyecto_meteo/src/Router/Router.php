<?php

namespace App\Routes;

class Router
{
    private $rutas = [];

    public function __construct()
    {
        $this->rutas['/'] = ['controller' => 'Controller', 'action' => 'index'];
        $this->rutas['/testdb'] = ['controller' => 'Controller','action' => 'testDB'];




    }

    public function handleRequest()
    {
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
