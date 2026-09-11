<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        return view('auth/login', ['title' => 'Entrar']);
    }

    public function attempt()
    {
        $email    = (string) $this->request->getPost('email');
        $password = (string) $this->request->getPost('password');
        $user     = model(UserModel::class)->findByEmail($email);

        if ($user === null || ! password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'E-mail ou senha inválidos.');
        }

        session()->regenerate();
        session()->set([
            'user_id'   => (int) $user['id'],
            'user_name' => $user['name'],
            'user_role' => $user['role'],
        ]);

        return redirect()->to('/');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');
    }
}
