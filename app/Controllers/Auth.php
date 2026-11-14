<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('user_id') !== null) {
            return redirect()->to(site_url('dashboard'));
        }

        return view('auth/login');
    }

    public function attemptLogin()
    {
        $email = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');
        $user = (new UserModel())
            ->where('email', $email)
            ->where('is_active', 1)
            ->first();

        if (
            ! $user
            || ! password_verify($password, $user['password_hash'])
            || ! in_array($user['role'], ['admin', 'petugas'], true)
        ) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email atau kata sandi tidak valid.');
        }

        session()->regenerate(true);
        session()->set([
            'user_id'   => (int) $user['id'],
            'user_name' => $user['name'],
            'user_role' => $user['role'],
        ]);

        return redirect()->to(site_url('dashboard'))
            ->with('success', 'Berhasil masuk ke SIPUS.');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to(site_url('login'))
            ->with('success', 'Anda telah keluar.');
    }
}
