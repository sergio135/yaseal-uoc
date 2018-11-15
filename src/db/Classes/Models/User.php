<?php
namespace Classes\Models;

class User {

    private $id;
    private $name;
    private $email;
    private $password;
    private $date_registered;
    private $role;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getDateRegistered() {
        return $this->date_registered;
    }

    public function setDateRegistered($date_registered) {
        $this->date_registered = $date_registered;
    }

    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public static function login($pdo, $email, $pass) {
        try {
            $stmt = $pdo->prepare("SELECT u.id, u.name, u.email, u.password, u.date_registered, r.name as role
                               FROM table_user u, table_role r
                               WHERE u.role_id = r.id
                               AND u.email = :email");
            $stmt->execute(['email' => $email]);
            $user = self::withRow($stmt->fetch());

            if (!password_verify($pass, $user->getPassword())) {
                throw new \Exception('Usuario o contraseña incorrecto');
            }
            return $user;
        } catch (\Exception $e) {
            switch ($e->getCode()) {
                case '42S22':
                    return 'Usuario no existe';
                default:
                    return $e->getMessage();
            }
        }
    }

    public static function insertNewUser($pdo, $name, $email, $pass, $role) {
        $stmt = $pdo->prepare("INSERT INTO table_user (name, email, password, date_registered, role_id)
                               VALUES (:name, :email, :password, CURRENT_DATE, :role)");
        $result = $stmt->execute(['name' => $name, 'email' => $email, 'password' => password_hash($pass, PASSWORD_DEFAULT), 'role' => $role]);
        return $result;
    }

    public static function withRow( array $row ) {
        $instance = new self();
        $instance->fill($row);
        return $instance;
    }

    protected function fill( array $row ) {
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->email = $row['email'];
        $this->password = $row['password'];
        $this->date_registered = $row['date_registered'];
        $this->role = $row['role'];
    }
}

?>