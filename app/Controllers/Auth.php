<?php

namespace App\Controllers;

use App\Models\UserModels;
use CodeIgniter\RESTful\ResourceController;

class Auth extends ResourceController
{
    protected $format = 'json';
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModels();
    }

    public function register()
    {
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
        ];

        log_message('debug', 'Data Array: ' . print_r($data, true));


        if ($this->userModel->checkUsernameExists($data['username']) && $this->userModel->checkEmailExists($data['email'])) {
            if ($this->userModel->registerUser($data)) {
                return $this->respondCreated(['message' => 'User registered successfully']);
            } else {
                return $this->fail('Failed to register user', 400);
            }
        } else {
            return $this->fail('Username or Email already exists', 400);
        }
    }

    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->userModel->loginUser($username, $password);

        if ($user) {
            return $this->respond([
                'message' => 'Login successful',
                'user' => $user
            ], 200);
        } else {
            return $this->failUnauthorized('Invalid username or password');
        }
    }
}
