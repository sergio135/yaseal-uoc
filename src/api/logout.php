<?php
///////////////////////////////////////////
// Configuracion de la ruta REST registro //
///////////////////////////////////////////
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Registro
//$app->post('/admin_panel/register', AdminPanelController::class . ':register');
$app->any('/logout', function(Request $req, Response $res, array $args) {
    session_destroy();
    return $res->withRedirect('./');
});

?>