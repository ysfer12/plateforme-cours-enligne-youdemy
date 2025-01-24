<?php
namespace App\Controllers\Enseignant;

use App\Models\Enseignant\UserModel;

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function logout() {
        $this->userModel->logout();
    }
}