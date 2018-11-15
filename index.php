<?php
///////////////////////////////////////////
// Configuracion general e inicializacion //
///////////////////////////////////////////

require './vendor/autoload.php';

session_start();

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host']   = 'localhost';
$config['db']['user']   = 'root';
$config['db']['pass']   = '';
$config['db']['dbname'] = 'p7_news_app';

$app = new \Slim\App(['settings' => $config]);

// Se crea un container que servira para añadir mas 
// dependencias al proyecto y enlazarlas con el framework.
$container = $app->getContainer();

// Añadios nueva depenencias para renderizar plantillas .phtml
$container['view'] = new \Slim\Views\PhpRenderer('./src/views/');

$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

$container['renderer'] = function ($c) {
    return new Slim\Views\PhpRenderer(__DIR__ . '/src/views');
};

// Importamos el resto de codigo de nuestra aplicacion 
// categorizado por funcionalidad.
require './src/routes/index.php';
require './src/api/index.php';

// Se lanza toda la aplicacion.
$app->run();
?>