<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Classes\Dao\UserDao;
use Classes\Dao\NewsDao;
use Classes\Models\User;

class AdminPanelController {
    protected $container;

    /**
     * AdminPanelController constructor.
     * @param $container
     */
    public function __construct($container) {
        $this->container = $container;
    }

    public function login($request, $response, $args) {
        $email = trim($request->getParam('email'));
        $pass = trim($request->getParam('password'));

        $userDao = new UserDao($this->container['db']);

        if (empty($email) || empty($pass)) {
            // Si no se rellena alguno de los campos del formulario se devuelve error
            return array("notification" => array("type" => "error",
                                                 "msg" => "Debe rellenar todos los campos"),
                         "email" => $email);
        } else {
            $user = $userDao->login($email, $pass);
            if (!($user instanceof User)) {
                // Si la BD ha devuelto error
                return array("notification" => array("type" => "error",
                                                     "msg" => $userDao->getError()),
                             "email" => $email);
            };
            return $user;
        }
    }

    public function register($request, $response, $args) {
        $name = trim($request->getParam('name'));
        $email = trim($request->getParam('email'));
        $pass = trim($request->getParam('password'));
        $role = trim($request->getParam('role'));

        if (empty($name) || empty($email) || empty($pass) || empty($role)) {
            // Si no se rellena alguno de los campos del formulario se devuelve error
            $args = array("notification" => array("type" => "error",
                                                  "msg" => "Debe rellenar todos los campos"),
                          "name" => $name,
                          "email" => $email,
                          "role" => $role);
            return $args;
        } else {
            $userDao = new UserDao($this->container['db']);
            $isInserted = $userDao->insertNewUser($name, $email, $pass, $role);
            if ($isInserted) {
                return $this->login($request, $response, $args);
            } else {
                return array("notification" => array("type" => "error",
                                                     "msg" => $userDao->getError()),
                             "name" => $name,
                             "email" => $email,
                             "role" => $role);
            }
        }
    }

    public function listAllNews($req, $res, $args) {
        $newsDao = new NewsDao($this->container['db']);
        if ($_SESSION['user']->getRole() == 'autor') {
            // Si es periodista solo se listaran las propias
            $news = $newsDao->listOwnNews($_SESSION['user']->getId());
        } else {
            $news = $newsDao->listAll();
        }

        if ($newsDao->getError()) {
            return $newsDao->getError();
        }

        return $news;
    }
}