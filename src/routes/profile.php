<?php
////////////////////////////////////////////////
// Configuracion de la ruta de autentificacion  //
////////////////////////////////////////////////
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Una ve iniciada la sesión se redirige al panel
$app->get('/admin_panel/profile', function (Request $req, Response $res, array $args) {
     if (!isset($_SESSION['user'])) {
         return $res->withRedirect('/admin_panel');
     }
     
    return $this->view->render($res, 'admin_panel/profile.phtml', $args);
});

?>