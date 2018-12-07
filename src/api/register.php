<?php
///////////////////////////////////////////
// Configuracion de la ruta REST registro //
///////////////////////////////////////////
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Registro
//$app->post('/admin_panel/register', AdminPanelController::class . ':register');
$app->post('/api/register', function(Request $req, Response $res, array $args) {
    $adminPanel = new AdminPanelController($this);
    $result = $adminPanel->register($req, $res, $args);
    if (array_key_exists("notification", $result)) {
        $this->view->render($res, '/admin_panel/home.phtml', $result);
        return;
    }
    $_SESSION['user'] = $result;
    return $res->withRedirect('panel', 301);
});

?>