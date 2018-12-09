<?php
////////////////////////////////////////////////
// Configuracion de la ruta de autentificacion  //
////////////////////////////////////////////////
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Una ve iniciada la sesión se redirige al panel
$app->get('/noticia', function (Request $req, Response $res, array $args) {
    $res = $this->view->render($res, 'noticia.phtml', []);
    return $res;
});

?>