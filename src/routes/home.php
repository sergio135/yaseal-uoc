<?php
////////////////////////////////////////
// Configuracion de la ruta de la home //
////////////////////////////////////////
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// metodo que maneja cada una de las llamadas a la ruta inicial
$app->get('/', function (Request $request, Response $response, array $args) {
    // $request: objeto que trae informacion sobre la peticion a la ruta.
    // $response: objeto con metodos que sirve para responder al cliente.
    // $args: deferentes argumentos pasados en la peticion.


    // renderizamos la plantilla home.phtml 
    $response = $this->view->render($response, 'home.phtml', []);

    // devolver siempre el objeto $response
    return $response;
});
?>