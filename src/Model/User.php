<?php

namespace ASPTest\Model;

use ASPTest\SQLiteConnection;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;

//create table user id, name, secondeName, email, password and create class User

class User
{
    public $id;
    public $name;
    public $secondName;
    public $email;
    public $password;
    public $age;


    public function __construct($id = null, $name = null, $secondName = null, $email = null, $password = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->secondName = $secondName;
        $this->email = $email;
        $this->password = $password;
    }




    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setSecondName($secondName)
    {
        $this->secondName = $secondName;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setAge($age)
    {
        $this->age = $age;
    }

    public function setPassword($password)
    {

        $options = [
            'cost' => 10,
            'salt' => random_bytes(22),
        ];
        $this->password = password_hash($password, PASSWORD_BCRYPT, $options);
    }

    public function getUserById($id)
    {
        $pdo = (new SQLiteConnection())->connect();
        $sql = "SELECT * FROM user WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $user = $stmt->fetchObject();
        if ($user) {
            $this->id = $user->id;
            $this->name = $user->name;
            $this->secondName = $user->secondName;
            $this->email = $user->email;
            $this->age = $user->age;
            $this->password = $user->password;



            return $this;
        }
    }

    public static function createUserFromArray($array)
    {
        return new User($array['id'], $array['name'], $array['secondName'], $array['email']);
    }

    public function save()
    {

        $pdo = (new SQLiteConnection())->connect();
        if ($this->id == null) {
            $sql = "INSERT INTO user (name, secondName, email, age) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$this->name, $this->secondName, $this->email, $this->age]);
            $this->id = $pdo->lastInsertId();
        } else {
            $sql = "UPDATE user SET name = ?, secondName = ?, email = ?, password = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$this->name, $this->secondName, $this->email, $this->password, $this->id]);
        }
    }
}
