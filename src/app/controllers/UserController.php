<?php 
require_once __DIR__ . "/../models/UserModel.php";
require_once __DIR__ ."/../helpers/pagination.php";

class UserController
{
    private $userModel; 
    
    public function __construct($pdo){
        $this->userModel = new UserModel($pdo);
    }

    public function getPaginatedUsers($page){ 
        return paginate($this->userModel->getConnection(), 'binotify_user', 'user_id ASC', $page) ?: [];
    }

    public function checkAvailability($type, $value){ 
        if ($type === 'username') { 
            return $this->userModel->userExistsByUsername($value);
        } elseif ($type === 'email'){ 
            return $this->userModel->userExistsByEmail($value);
        }
        return false;
    }

    public function registerUser($username, $email, $password){ 
        if ($this->userModel->userExists($email, $username)){ 
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        return $this->userModel->createUser($username, $email, $hashedPassword);
    }

}
