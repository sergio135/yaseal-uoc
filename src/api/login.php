<?php
/////////////////////////////////////////////////
// Configuracion de la ruta REST inicio session //
/////////////////////////////////////////////////
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Login
$app->post('/api/login', function (Request $req, Response $res, array $args) {

    // los datos que vienen del formulario
    $dataForm = json_decode($req->getBody());


    $adminPanel = new AdminPanelController($this);
    $result = $adminPanel->login($req, $res, $args);
    if (array_key_exists("notification", $result)) {
        $this->view->render($res, '/admin_panel/auth.phtml', $result);
        return;
    }
    $_SESSION['user'] = $result;
    return $res->withRedirect('/yaseal-local/admin_panel/panel', 301);


    /////////////////////////////////////////////////
    // Aqui solo hay que devolver una respuesta afirmativa o un error, y ya se controla la redireccion desde el frontEnd con JS
    /////////////////////////////////////////////////

    $statusCode = 200;
    // la propiedad Error solo viene si hay algun error, si no, se envian los datos en la propiedad data
    $data = [
        'error' => [
            'messagge' => 'El usuario no existe'
        ],
        'data' => [
            'id' => "{$result->getId()}",
            'name' => '{$result->getId()}',
            'email' => '{$result->getId()}'
        ]
    ];

    // devolver siempre el objeto $response
    return $res->withJson($data, $statusCode);
});

?>