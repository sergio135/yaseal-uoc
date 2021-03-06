<?php
////////////////////////////////////////////////
// Configuracion de la ruta de autentificacion  //
////////////////////////////////////////////////
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Editar noticias
$app->get('/admin_panel/edit/{id}', function (Request $req, Response $res, array $args) {
    $route = $req->getAttribute('route');
    $newsId = $route->getArgument('id');

    $newsController = new NewsController($this);
    $categories = $newsController->getCategories();
    $args = $newsController->getNewsById($newsId);
    if ($categories) {
        $args['categories'] = $categories;
    }

    $res = $this->view->render($res, 'admin_panel/add_edit_news.phtml', $args);
    return $res;
});

$app->post('/admin_panel/edit/{id}', function (Request $req, Response $res, array $args) {
    $route = $req->getAttribute('route');
    $newsId = $route->getArgument('id');

    $newsController = new NewsController($this);
    $result = $newsController->updateNews($req, $newsId);

    if (array_key_exists('notification', $result)) {
        $this->view->render($res, '/admin_panel/add_edit_news.phtml', $result);
        return;
    }
    $_SESSION['notification'] = array("type" => "success",
        "msg" => "Ha modificado correctamente una nueva publicación");
    return $res->withRedirect("/admin_panel/panel", 301);
});

?>