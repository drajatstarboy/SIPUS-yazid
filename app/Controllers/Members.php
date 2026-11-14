<?php

namespace App\Controllers;

use App\Models\MemberModel;

class Members extends BaseController
{
    public function index()
    {
        $model = new MemberModel();
        $search = trim((string) $this->request->getGet('q'));
        $status = $this->request->getGet('status');
        $status = in_array($status, ['active', 'inactive'], true) ? $status : '';

        $builder = $model->orderBy('name', 'ASC');

        if ($search !== '') {
            $builder->groupStart()
                ->like('member_code', $search)
                ->orLike('name', $search)
                ->orLike('class_name', $search)
                ->orLike('phone', $search)
                ->groupEnd();
        }

        if ($status !== '') {
            $builder->where('status', $status);
        }

        return view('members/index', [
            'members' => $builder->findAll(),
            'filters' => [
                'q'      => $search,
                'status' => $status,
            ],
        ]);
    }

    public function new()
    {
        return view('members/form', [
            'member' => null,
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function create()
    {
        $rules = [
            'member_code' => 'required|max_length[30]|is_unique[members.member_code]',
            'name'        => 'required|min_length[2]|max_length[150]',
            'class_name'  => 'permit_empty|max_length[50]',
            'phone'       => 'permit_empty|max_length[30]',
            'status'      => 'required|in_list[active,inactive]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $model = new MemberModel();
        $model->insert([
            'member_code' => strtoupper(trim((string) $this->request->getPost('member_code'))),
            'name'       => trim((string) $this->request->getPost('name')),
            'class_name' => trim((string) $this->request->getPost('class_name')) ?: null,
            'phone'      => trim((string) $this->request->getPost('phone')) ?: null,
            'status'     => $this->request->getPost('status'),
        ]);

        return redirect()->to('/members')
            ->with('success', 'Data anggota berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $model = new MemberModel();
        $member = $model->find($id);

        if (! $member) {
            return redirect()->to('/members')
                ->with('error', 'Data anggota tidak ditemukan.');
        }

        return view('members/form', [
            'member' => $member,
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function update($id)
    {
        $model = new MemberModel();
        $member = $model->find($id);

        if (! $member) {
            return redirect()->to('/members')
                ->with('error', 'Data anggota tidak ditemukan.');
        }

        $rules = [
            'member_code' => "required|max_length[30]|is_unique[members.member_code,id,{$id}]",
            'name'        => 'required|min_length[2]|max_length[150]',
            'class_name'  => 'permit_empty|max_length[50]',
            'phone'       => 'permit_empty|max_length[30]',
            'status'      => 'required|in_list[active,inactive]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $model->update($id, [
            'member_code' => strtoupper(trim((string) $this->request->getPost('member_code'))),
            'name'        => trim((string) $this->request->getPost('name')),
            'class_name'  => trim((string) $this->request->getPost('class_name')) ?: null,
            'phone'       => trim((string) $this->request->getPost('phone')) ?: null,
            'status'      => $this->request->getPost('status'),
        ]);

        return redirect()->to('/members')
            ->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function delete($id)
    {
        $model = new MemberModel();
        $member = $model->find($id);

        if (! $member) {
            return redirect()->to('/members')
                ->with('error', 'Data anggota tidak ditemukan.');
        }

        $used = db_connect()->table('loans')
            ->where('member_id', $id)
            ->countAllResults();

        if ($used > 0) {
            return redirect()->to('/members')
                ->with('error', 'Anggota sudah memiliki riwayat peminjaman dan tidak dapat dihapus.');
        }

        $model->delete($id);

        return redirect()->to('/members')
            ->with('success', 'Data anggota berhasil dihapus.');
    }
}
