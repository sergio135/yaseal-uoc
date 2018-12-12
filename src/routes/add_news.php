<?php
////////////////////////////////////////////////
// Configuracion de la ruta de autentificacion  //
////////////////////////////////////////////////
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Añadir noticias
$app->get('/admin_panel/add', function (Request $req, Response $res, array $args) {
    $newsController = new NewsController($this);
    $categories = $newsController->getCategories();
    $res = $this->view->render($res, 'admin_panel/add_edit_news.phtml', ['categories'=>$categories]);
    return $res;
});
$app->post('/admin_panel/add', function (Request $req, Response $res, array $args) {
    $newsController = new NewsController($this);
    $result = $newsController->addNews($req, $res, $args);

    if (array_key_exists('notification', $result)) {
        $this->view->render($res, '/admin_panel/add_edit_news.phtml', $result);
        return;
    }
    $_SESSION['notification'] = array("type" => "success",
                                      "msg" => "Ha creado correctamente una nueva publicación");
    return $res->withRedirect('panel', 301);
});

?>