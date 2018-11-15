<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../Models/User.php';

class AdminPanelController {
    protected $container;
    protected $pdo;

    /**
     * AdminPanelController constructor.
     * @param $container
     */
    public function __construct($container) {
        $this->container = $container;
        $this->pdo = $container->get('db');
    }

    public function login($request, $response, $args) {
        $email = trim($request->getParam('email'));
        $pass = trim($request->getParam('password'));

        if (empty($email) || empty($pass)) {
            // Si no se rellena alguno de los campos del formulario se devuelve error
            $args = array("error" => "Debe rellenar todos los campos", 'email' => $email);
            $this->renderError($response, '/admin_panel/home.phtml', $args);
        } else {
            $result = \Classes\Models\User::login($this->pdo, $email, $pass);
            if (!($result instanceof \Classes\Models\User)) {
                // Si la BD ha devuelto error
                $args = array("error" => $result, 'email' => $email);
                $this->renderError($response, '/admin_panel/home.phtml', $args);
                return;
            };
            $args = array("user" => $result);
            $_SESSION['user'] = $result;
            $this->container->renderer->render($response, '/admin_panel/panel.phtml', $args);
        }
    }

    public function register($request, $response, $args) {
        $name = trim($request->getParam('name'));
        $email = trim($request->getParam('email'));
        $pass = trim($request->getParam('password'));
        $role = trim($request->getParam('role'));

        if (empty($name) || empty($email) || empty($pass) || empty($role)) {
            // Si no se rellena alguno de los campos del formulario se devuelve error
            $args = array("error" => "Debe rellenar todos los campos",
                          "name" => $name,
                          "email" => $email,
                          "role" => $role);
            $this->renderError($response, '/admin_panel/home.phtml', $args);
        } else {
            $isInserted = \Classes\Models\User::insertNewUser($this->pdo, $name, $email, $pass, $role);
            if ($isInserted) {
                $this->login($request, $response, $args);
            }
        }
    }

    public function renderError($response, $path, $args) {
        $this->container->renderer->render($response, $path, $args);
    }
}