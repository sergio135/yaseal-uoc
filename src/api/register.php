<?php
///////////////////////////////////////////
// Configuracion de la ruta REST registro //
///////////////////////////////////////////
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/api/register', function(Request $req, Response $res, array $args) {

    $dataForm = json_decode($req->getBody(), true);
   
    $adminPanel = new AdminPanelController($this);
     $result = $adminPanel->register($dataForm);
    
    
    if (array_key_exists("notification", $result)) {
        return $res->withJson(['error' => $result['notification']], 500);  
    }
    $_SESSION['user'] = $result;
    return $res->withJson($result, 200);  
});

?>