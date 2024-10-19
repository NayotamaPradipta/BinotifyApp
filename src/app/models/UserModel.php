<?php
class UserModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    public function getConnection()
    {
        return $this->pdo;
    }

    public function userExistsByUsername($username){ 
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM binotify_user WHERE username = :username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function userExistsByEmail($email){ 
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM binotify_user WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function userExists($email, $username){ 
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM binotify_user WHERE email = :email OR username = :username");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function createUser($username, $email, $hashedPassword){ 
        try { 
            $stmt = $this->pdo->prepare("INSERT INTO binotify_user (username, email, password, isadmin) VALUES (:username, :email, :password, FALSE)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            return $stmt->execute();
        } catch (PDOException $e) { 
            return false; 
        }
    }
}
