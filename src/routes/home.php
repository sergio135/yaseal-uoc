<?php
////////////////////////////////////////
// Configuracion de la ruta de la home //
////////////////////////////////////////
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->add(function (Request $request, Response $response, callable $next) {
    $uri = $request->getUri();
    $path = $uri->getPath();
    if ($path != '/' && substr($path, -1) == '/') {
        // permanently redirect paths with a trailing slash
        // to their non-trailing counterpart
        $uri = $uri->withPath(substr($path, 0, -1));

        if($request->getMethod() == 'GET') {
            return $response->withRedirect((string)$uri, 301);
        }
        else {
            return $next($request->withUri($uri), $response);
        }
    }

    return $next($request, $response);
});

// metodo que maneja cada una de las llamadas a la ruta inicial
$app->get('/', function (Request $request, Response $response, array $args) {
    // $request: objeto que trae informacion sobre la peticion a la ruta.
    // $response: objeto con metodos que sirve para responder al cliente.
    // $args: deferentes argumentos pasados en la peticion.

    $newsController = new NewsController($this);

    $_SESSION['news'] = array(
        '' => array_slice($newsController->listAllPublishedNews(), 0, 3),
        'Internacional' => array_slice($newsController->getNewsByCategoryId(1), 0, 3),
        'Local' => array_slice($newsController->getNewsByCategoryId(2), 0, 3),
        'Deportes' => array_slice($newsController->getNewsByCategoryId(3), 0, 3),
        'El tiempo' => array_slice($newsController->getNewsByCategoryId(4), 0, 3)
      );
      
    // renderizamos la plantilla panel.phtml
    return $this->view->render($response, 'home.phtml', []);
});


$app->get('/internacional', function (Request $request, Response $response, array $args) {
    $newsController = new NewsController($this);
    $_SESSION['news'] = array('internacionales' => $newsController->getNewsByCategoryId(1));
      
    return $this->view->render($response, 'noticiasCategoria.phtml', []);
});

$app->get('/local', function (Request $request, Response $response, array $args) {
    $newsController = new NewsController($this);
    $_SESSION['news'] = array('locales' => $newsController->getNewsByCategoryId(2));
      
    return $this->view->render($response, 'noticiasCategoria.phtml', []);
});

$app->get('/deportes', function (Request $request, Response $response, array $args) {
    $newsController = new NewsController($this);
    $_SESSION['news'] = array('sobre deportes' => $newsController->getNewsByCategoryId(3));
      
    return $this->view->render($response, 'noticiasCategoria.phtml', []);
});

$app->get('/tiempo', function (Request $request, Response $response, array $args) {
    $newsController = new NewsController($this);
    $_SESSION['news'] = array('sobre el tiempo' => $newsController->getNewsByCategoryId(4));
      
    return $this->view->render($response, 'noticiasCategoria.phtml', []);
});

//$app->post('/admin_panel/edit/{id}', function (Request $req, Response $res, array $args) {
//    $route = $req->getAttribute('route');
//    $newsId = $route->getArgument('id');
//
//    $newsController = new NewsController($this);
//    $args = $newsController->updateNews($req, $newsId);
//
//    $res = $this->view->render($res, 'admin_panel/add_edit_news.phtml', $args);
//    return $res;
//});
?>