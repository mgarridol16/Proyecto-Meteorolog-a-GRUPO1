<?php

namespace App\Controllers;


use Dotenv\Dotenv;
use App\Models\Datos;
use App\Models\Temperatura;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

use App\Models\Database;
use App\Models\Modelo_temperatura;

class Controller_miguel_temperatura
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



//*TENDREMOS UN CONTROLLER Y VAMOS A MOSTRAR LA LLUVIA ACOMULADA POR 24H Y TAMBIEN MOSTRAREMOS UN GRAFICO, Y QUE EL USUARIO PUEDA ELEGIR DE QUE DIA A QUE DIA VER LA LLUVIA ACOMULADA
//*MOSTRAREMOS TAMBIEN LLUVIA ACOMULADA EN EL ULTIMO AÑO

  /**Tabla
• Filtro por fechas
• Gráfica (Chart.js)
• Paginación opcional */

  public function historicoLluviaAcomulada($request)
  {
    //
  }
}
