<?php
////////////////////////////////////////////////
// Configuracion de la ruta de autentificacion  //
////////////////////////////////////////////////
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Una ve iniciada la sesión se redirige al panel
$app->get('/admin_panel/panel', function (Request $req, Response $res, array $args) {
    // if (!isset($_SESSION['user'])) {
    //     return $res->withRedirect('/admin_panel');
    // }
    // $adminPanel = new AdminPanelController($this);
    // $news = $adminPanel->listAllNews($req, $res, $args);
    // $args = array("user"=>$_SESSION['user'], "news" => $news);
    // if (isset($_SESSION['notification'])) {
    //     $args['notification'] = $_SESSION['notification'];
    //     unset($_SESSION['notification']);
    // }
    return $this->view->render($res, 'admin_panel/panel.phtml', $args);
});

?>