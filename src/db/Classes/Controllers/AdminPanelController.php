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

    public function login($dataForm) {
        $email = $dataForm['email'];
        $pass = $dataForm['password'];

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

    public function register($dataForm) {
        $name = $dataForm['name'];
        $email = $dataForm['email'];
        $pass = $dataForm['password'];
        $role = $dataForm['role'];

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
                return $this->login($dataForm);
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

    public function updateUser($dataForm) {
        $id = $_SESSION['user']->getId();
        $name = $dataForm['name'];
        $email = $dataForm['email'];
        $password = $dataForm['password'];

        $pass = null;
        if (!empty($password)) {
            $pass = password_hash($password, PASSWORD_DEFAULT);
        }
        $userDao = new UserDao($this->container['db']);
        $result = $userDao->updateUser($id, $name, $email, $pass);
        if ($userDao->getError()) {
            return $userDao->getError();
        }
        $_SESSION['user'] = UserDao::getbyId($userDao->getConn(), $id);
        return $result;
    }
}