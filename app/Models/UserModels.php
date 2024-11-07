<?php

namespace App\Models;

use CodeIgniter\Model;

class userModels extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'email', 'password'];
    protected $useTimestamps = false;

    public function registerUser($data)
    {
        return $this->insert($data);
    }

    public function loginUser($username, $password)
    {
        // Cari pengguna berdasarkan username atau email
        $user = $this->where('username', $username)
                     ->orWhere('email', $username)
                     ->first();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    public function checkUsernameExists($username)
    {
        return $this->where('username', $username)->countAllResults() === 0;
    }

    public function checkEmailExists($email)
    {
        return $this->where('email', $email)->countAllResults() === 0;
    }
}
