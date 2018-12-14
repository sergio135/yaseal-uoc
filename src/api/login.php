<?php
/////////////////////////////////////////////////
// Configuracion de la ruta REST inicio session //
/////////////////////////////////////////////////
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Login
$app->post('/api/login', function (Request $req, Response $res, array $args) {

    // los datos que vienen del formulario
    $dataForm = json_decode($req->getBody(), true);
    
    $adminPanel = new AdminPanelController($this);
    $result = $adminPanel->login($dataForm);
    
    
    if (array_key_exists("notification", $result)) {
        return $res->withJson(['error' => $result['notification']], 500);  
    }
    $_SESSION['user'] = $result;
    return $res->withJson($result, 200);  
});

?>