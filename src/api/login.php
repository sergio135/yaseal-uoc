<?php
/////////////////////////////////////////////////
// Configuracion de la ruta REST inicio session //
/////////////////////////////////////////////////
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Login
$app->post('/api/login', function (Request $req, Response $res, array $args) {

    $dataForm = json_decode($req->getBody());
    error_log($dataForm);


    // $adminPanel = new AdminPanelController($this);
    // $result = $adminPanel->login($req, $res, $args);
    // if (array_key_exists("notification", $result)) {
    //     $this->view->render($res, '/admin_panel/home.phtml', $result);
    //     return;
    // }
    // $_SESSION['user'] = $result;
    // return $res->withRedirect('panel', 301);


    $statusCode = 200;
    // la propiedad Error solo viene si hay algun error, si no, se envian los datos en la propiedad data
    $data = [
        'error' => [
            'messagge' => 'El usuario no existe'
        ],
        'data' => [
            'id' => 'a6s7d8a6sd',
            'name' => 'Bob Jason',
            'email' => 'asdasd@gmail.com'
        ]
    ];

    // devolver siempre el objeto $response
    return $res->withJson($data, $statusCode);
});

?>