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
}
