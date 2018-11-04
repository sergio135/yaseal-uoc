<?php
/////////////////////////////////////////////////
// Configuracion de la ruta REST inicio session //
/////////////////////////////////////////////////
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// metodo que maneja cada una de las llamadas a la ruta '/api/login'
$app->get('/api/login', function (Request $request, Response $response, array $args) {
    // $request: objeto que trae informacion sobre la peticion a la ruta.
    // $response: objeto con metodos que sirve para responder al cliente.
    // $args: deferentes argumentos pasados en la peticion.

    // aun por terminar
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    // devolver siempre el objeto $response
    return $response;
});

?>