<?php
///////////////////////////////////////////
// Configuracion general e inicializacion //
///////////////////////////////////////////

require './vendor/autoload.php';
$app = new \Slim\App;

// Se crea un container que servira para añadir mas 
// dependencias al proyecto y enlazarlas con el framework.
$container = $app->getContainer();

// Añadios nueva depenencias para renderizar plantillas .phtml
$container['view'] = new \Slim\Views\PhpRenderer('./src/views/');

// Importamos el resto de codigo de nuestra aplicacion 
// categorizado por funcionalidad.
require './src/routes/index.php';
require './src/api/index.php';

// Se lanza toda la aplicacion.
$app->run();
?>