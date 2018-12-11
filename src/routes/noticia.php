<?php
////////////////////////////////////////////////
// Configuracion de la ruta de autentificacion  //
////////////////////////////////////////////////
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Una ve iniciada la sesión se redirige al panel
$app->get('/noticia/{id}', function (Request $req, Response $res, array $args) {
    $route = $req->getAttribute('route');
    $newsId = $route->getArgument('id');

    $newsController = new NewsController($this);
    $_SESSION['new'] = $newsController->getNewsById($newsId);
    $res = $this->view->render($res, 'noticia.phtml', []);
    return $res;
});

$app->get('/noticia', function (Request $req, Response $res, array $args) {

    $res = $this->view->render($res, 'noticia.phtml', []);
    return $res;
});

?>