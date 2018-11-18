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

    public function fill(array $row) {
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->email = $row['email'];
        $this->password = $row['password'];
        $this->date_registered = $row['date_registered'];
        $this->role = $row['role'];
    }
}

?>