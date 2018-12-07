<?php
////////////////////////////////////////////////
// Configuracion de la ruta de autentificacion  //
////////////////////////////////////////////////
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/admin_panel', function (Request $req, Response $res, array $args) {
    // $request: objeto que trae informacion sobre la peticion a la ruta.
    // $response: objeto con metodos que sirve para responder al cliente.
    // $args: deferentes argumentos pasados en la peticion.

    // rendirije al panel si esta logeado
    if (isset($_SESSION['user'])) {
        return $res->withRedirect('admin_panel/panel');
    }

    // renderizamos la plantilla auth.phtml
    return $this->view->render($res, 'admin_panel/auth.phtml', []);
});

?>