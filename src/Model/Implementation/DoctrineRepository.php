<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 18/04/2018
 * Time: 16:48
 */

namespace BeProject\Model\Implementation;

use PDO;
use BeProject\Model\Interfaces\IDoctrineRepository;
use Doctrine\DBAL\Connection;
use BeProject\Model\User;

class DoctrineRepository implements IDoctrineRepository{
    const DATE_FORMAT = 'Y-m-d H:i:s';
    private $connection;

    function __construct(Connection $connection){
        $this->connection = $connection;
    }
    public function save(User $user){

        $sql = "INSERT INTO user (username, email, password, createdAt, updatedAt, validation) VALUES(:username, :email, :password, :createdAt, :updatedAt, :validation)";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue("username", $user->getUsername(),'string');

        $stmt->bindValue("email", $user->getEmail(),'string');

        $stmt->bindValue("password", md5($user->getPassword()),'string');

        $stmt->bindValue("createdAt", $user->getCreatedAt()->format(self::DATE_FORMAT));
        $stmt->bindValue("updatedAt", $user->getUpdatedAt()->format(self::DATE_FORMAT));
        $stmt->bindValue("validation", $user->getValidation(), 'string');
        $stmt->execute();

    }

    public function userExists($username){
        $sql = "SELECT * FROM user WHERE username = '$username'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return isset($row['username']);
    }

    public function emailExists($email){
        $sql = "SELECT * FROM user WHERE email = '$email'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return isset($row['email']);
    }

    public function getPwdByUsername(array $rawData){
        $aux = $rawData['user_name'];
        $sql = "SELECT * FROM user WHERE username = '$aux'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['password'];

    }

    public function getPwdByEmail(array $rawData){
        $aux = $rawData['user_name'];
        $sql = "SELECT * FROM user WHERE email = '$aux'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['password'];

    }


    public function getEmailFromUsername($username){
        $sql = "SELECT * FROM user WHERE username = '$username'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['email'];
    }

    public function getUsrName(array $rawData){
        $aux = $rawData['user_name'];
        $sql = "SELECT * FROM user WHERE email = '$aux'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row == null){
            $aux = $rawData['user_name'];
            $sql = "SELECT * FROM user WHERE username = '$aux'";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return $row['username'];

    }

    public function getUsrIdFromUsername($username){
        $sql = "SELECT id FROM user WHERE username = '$username'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['id'];
    }

    public function getUsrIdFromEmail($email){
        $sql = "SELECT id FROM user WHERE email = '$email'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['id'];
    }


    public function getUsr( $data){
        
        $sql = "SELECT * FROM user WHERE username = '$data'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;

    }

    public function deleteUsr( $data){
        $sql = "DELETE FROM user WHERE username = '$data'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
    }

    public function updateEmail($user_id, array $data){
        $sql = "UPDATE user SET email = '$data[email]' WHERE username = '$user_id'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
    }

    public function updatePassword($user_id, array $data){
        $aux = md5($data['password']);
        $sql = "UPDATE user SET password = '$aux' WHERE username = '$user_id'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
    }


    public function checkUser($user_id){
        $sql = "UPDATE user SET activated = TRUE WHERE username = '$user_id'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
    }
    public function getValidationLink($user_id){
        $sql = "SELECT * FROM user WHERE username = '$user_id'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['validation'];
    }

}