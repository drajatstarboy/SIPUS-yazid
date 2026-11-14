<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    public function index()
    {
        return view('users/index', [
            'users'  => (new UserModel())->select('id, name, email, role, is_active, created_at')
                ->orderBy('name', 'ASC')
                ->findAll(),
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function create()
    {
        $rules = [
            'name'     => 'required|min_length[2]|max_length[100]',
            'email'    => 'required|valid_email|max_length[150]|is_unique[users.email]',
            'password' => 'required|min_length[12]|max_length[255]',
            'role'     => 'required|in_list[admin,petugas]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to(site_url('users'))
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        (new UserModel())->insert([
            'name'          => trim((string) $this->request->getPost('name')),
            'email'         => trim((string) $this->request->getPost('email')),
            'password_hash' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'          => $this->request->getPost('role'),
            'is_active'     => 1,
        ]);

        return redirect()->to(site_url('users'))
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }
}
