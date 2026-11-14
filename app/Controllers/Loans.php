<?php

namespace App\Controllers;

use App\Models\BookModel;
use App\Models\MemberModel;

class Loans extends BaseController
{
    public function index()
    {
        $db = db_connect();
        $loans = [];

        if ($db->tableExists('loans')) {
            $loans = $db->table('loans as l')
                ->select('l.*, m.name AS member_name, u.name AS user_name')
                ->join('members as m', 'm.id = l.member_id', 'left')
                ->join('users as u', 'u.id = l.user_id', 'left')
                ->orderBy('l.loan_date', 'DESC')
                ->get()
                ->getResultArray();
        }

        return view('loans/index', [
            'loans' => $loans,
            'title' => 'Data Peminjaman',
        ]);
    }

    public function new()
    {
        $members = (new MemberModel())
            ->where('status', 'active')
            ->orderBy('name', 'ASC')
            ->findAll();

        $books = (new BookModel())
            ->where('is_active', 1)
            ->where('stock_available >', 0)
            ->orderBy('title', 'ASC')
            ->findAll();

        return view('loans/form', [
            'loan' => null,
            'members' => $members,
            'books' => $books,
            'errors' => session()->getFlashdata('errors') ?? [],
            'title' => 'Tambah Peminjaman',
        ]);
    }

    public function create()
    {
        $rules = [
            'member_id' => 'required|integer|is_not_unique[members.id]',
            'book_id' => 'required|integer|is_not_unique[books.id]',
            'loan_date' => 'required|valid_date[Y-m-d]',
            'due_date' => 'required|valid_date[Y-m-d]',
            'quantity' => 'required|integer|greater_than[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $db = db_connect();
        $bookId = (int) $this->request->getPost('book_id');
        $quantity = (int) $this->request->getPost('quantity');
        $book = $db->table('books')->where('id', $bookId)->get()->getRowArray();

        if (! $book) {
            return redirect()->back()->withInput()->with('errors', [
                'book_id' => 'Buku tidak ditemukan.',
            ]);
        }

        if ((int) $book['stock_available'] < $quantity) {
            return redirect()->back()->withInput()->with('errors', [
                'quantity' => 'Jumlah pinjaman melebihi stok yang tersedia.',
            ]);
        }

        $loanCode = 'LOAN-' . date('Ymd') . '-' . str_pad((int) $db->table('loans')->countAllResults() + 1, 4, '0', STR_PAD_LEFT);
        $loanId = $db->table('loans')->insert([
            'loan_code' => $loanCode,
            'member_id' => (int) $this->request->getPost('member_id'),
            'user_id' => (int) session()->get('user_id'),
            'loan_date' => $this->request->getPost('loan_date'),
            'due_date' => $this->request->getPost('due_date'),
            'status' => 'active',
            'notes' => trim((string) $this->request->getPost('notes')) ?: null,
            'created_at' => date('Y-m-d H:i:s'),
        ], true);

        $db->table('loan_details')->insert([
            'loan_id' => $loanId,
            'book_id' => $bookId,
            'quantity' => $quantity,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $db->table('books')
            ->where('id', $bookId)
            ->set('stock_available', (int) $book['stock_available'] - $quantity)
            ->update();

        return redirect()->to('/loans')
            ->with('success', 'Peminjaman berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $db = db_connect();
        $loan = $db->table('loans')->where('id', $id)->get()->getRowArray();

        if (! $loan) {
            return redirect()->to('/loans')->with('error', 'Data peminjaman tidak ditemukan.');
        }

        return view('loans/form', [
            'loan' => $loan,
            'members' => (new MemberModel())->orderBy('name', 'ASC')->findAll(),
            'books' => (new BookModel())->orderBy('title', 'ASC')->findAll(),
            'errors' => session()->getFlashdata('errors') ?? [],
            'title' => 'Edit Peminjaman',
        ]);
    }

    public function update($id)
    {
        $db = db_connect();
        $loan = $db->table('loans')->where('id', $id)->get()->getRowArray();

        if (! $loan) {
            return redirect()->to('/loans')->with('error', 'Data peminjaman tidak ditemukan.');
        }

        $db->table('loans')->where('id', $id)->update([
            'member_id' => (int) $this->request->getPost('member_id'),
            'loan_date' => $this->request->getPost('loan_date'),
            'due_date' => $this->request->getPost('due_date'),
            'status' => $this->request->getPost('status') ?: 'active',
            'notes' => trim((string) $this->request->getPost('notes')) ?: null,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/loans')->with('success', 'Data peminjaman berhasil diperbarui.');
    }

    public function delete($id)
    {
        $db = db_connect();
        $loan = $db->table('loans')->where('id', $id)->get()->getRowArray();

        if (! $loan) {
            return redirect()->to('/loans')->with('error', 'Data peminjaman tidak ditemukan.');
        }

        $db->table('loan_details')->where('loan_id', $id)->delete();
        $db->table('loans')->where('id', $id)->delete();

        return redirect()->to('/loans')->with('success', 'Data peminjaman berhasil dihapus.');
    }
}
