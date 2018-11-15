<?php
////////////////////////////////////////
// Configuracion de la ruta de la home //
////////////////////////////////////////
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../db/Classes/Controllers/AdminPanelController.php';

// metodo que maneja cada una de las llamadas a la ruta inicial
$app->get('/', function (Request $request, Response $response, array $args) {
    // $request: objeto que trae informacion sobre la peticion a la ruta.
    // $response: objeto con metodos que sirve para responder al cliente.
    // $args: deferentes argumentos pasados en la peticion.


    // renderizamos la plantilla panel.phtml
    $response = $this->view->render($response, 'home.phtml', []);

    // devolver siempre el objeto $response
    return $response;
});

$app->get('/admin_panel', function (Request $request, Response $response, array $args) {
    $response = $this->view->render($response, 'admin_panel/home.phtml', []);
    return $response;
});

// Login y Registro
$app->post('/admin_panel/login', AdminPanelController::class . ':login');
$app->post('/admin_panel/register', AdminPanelController::class . ':register');
?>