<?php

namespace App\Controllers;


use Dotenv\Dotenv;
use App\Models\Datos;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

use App\Models\Database;

class Controller_victor_about
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

    public function about(){
        echo $this->twig->render("about.html.twig");
    }
    

}
