<?php
namespace Classes\Dao;

use Classes\Models\User;
use Exception;
use PDO;

class UserDao {

    private $conn;
    private $error;

    /**
     * UserDao constructor.
     * @param $conn
     */
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getConn() {
        return $this->conn;
    }

    public function getError() {
        return $this->error;
    }

    public function setError($error) {
        $this->error = $error;
    }

    public static function getbyId($db, $id) {
        $user = new User();
        try {
            $stmt = $db->prepare("SELECT u.id, u.name, u.email, u.password, u.date_registered, r.name as role
                               FROM table_user u, table_role r
                               WHERE u.role_id = r.id
                               AND u.id = :id");
            $stmt->execute(['id' => $id]);
            while ($row = $stmt->fetch()) {
                $user->fill($row);
            }
            return $user;
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }

    public function login($email, $pass) {
        $db = $this->getConn();
        $db->beginTransaction();
        try {
            $stmt = $db->prepare("SELECT u.id, u.name, u.email, u.password, u.date_registered, r.name as role
                               FROM table_user u, table_role r
                               WHERE u.role_id = r.id
                               AND u.email = :email");
            $stmt->execute(['email' => $email]);
            $row = $stmt->fetch();
            $user = new User();
            if (!$row) throw new Exception('El usuario no existe');
            $user->fill($row);

            if (!password_verify($pass, $user->getPassword())) {
                throw new Exception('Usuario o contraseña incorrecto');
            }
            $db->commit();
            return $user;
        } catch (Exception $e) {
            $db->rollBack();
            switch ($e->getCode()) {
                case '42S22':
                    $this->setError('Usuario no existe');
                    break;
                default:
                    $this->setError($e->getMessage());
            }
        }
    }

    public function insertNewUser($name, $email, $pass, $role) {
        $db = $this->getConn();
        $stmt = $db->prepare("INSERT INTO table_user (name, email, password, date_registered, role_id)
                               VALUES (:name, :email, :password, CURRENT_DATE, :role)");
        $result = $stmt->execute(['name' => $name, 'email' => $email, 'password' => password_hash($pass, PASSWORD_DEFAULT), 'role' => $role]);
        return $result;
    }

    public function updateUser($id, $name, $email, $pass = null) {
        $db = $this->getConn();
        $db->beginTransaction();
        try {
            $sql = "UPDATE table_user 
                    SET name = :name,
                        email = :email";
            if ($pass) $sql .= ", password = :password";
            $sql .= " WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam('name', $name);
            $stmt->bindParam('email', $email);
            if ($pass) $stmt->bindParam('password', $pass);
            $stmt->bindParam('id', $id, PDO::PARAM_INT);

            $result = $stmt->execute();
            if ($result) {
                $db->commit();
            }
            return $result;
        } catch (Exception $e) {
            $db->rollBack();
            var_dump($this->getError()); die();
            $this->setError($e->getMessage());
        }
    }
}