<?php

namespace App\Controllers;

class Returns extends BaseController
{
    public function index()
    {
        $db = db_connect();
        $returns = [];

        if ($db->tableExists('loan_returns')) {
            $returns = $db->table('loan_returns as r')
                ->select('r.*, u.name AS user_name, b.title AS book_title, m.name AS member_name')
                ->join('users as u', 'u.id = r.user_id', 'left')
                ->join('loan_details as ld', 'ld.id = r.loan_detail_id', 'left')
                ->join('books as b', 'b.id = ld.book_id', 'left')
                ->join('loans as l', 'l.id = ld.loan_id', 'left')
                ->join('members as m', 'm.id = l.member_id', 'left')
                ->orderBy('r.returned_at', 'DESC')
                ->get()
                ->getResultArray();
        }

        return view('returns/index', [
            'returns' => $returns,
            'title' => 'Pengembalian Buku',
        ]);
    }

    public function create()
    {
        $rules = [
            'loan_detail_id' => 'required|integer|is_not_unique[loan_details.id]',
            'quantity' => 'required|integer|greater_than[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = db_connect();
        $loanDetailId = (int) $this->request->getPost('loan_detail_id');
        $quantity = (int) $this->request->getPost('quantity');
        $loanDetail = $db->table('loan_details')->where('id', $loanDetailId)->get()->getRowArray();

        if (! $loanDetail) {
            return redirect()->back()->withInput()->with('errors', [
                'loan_detail_id' => 'Data peminjaman tidak ditemukan.',
            ]);
        }

        $book = $db->table('books')->where('id', (int) $loanDetail['book_id'])->get()->getRowArray();
        if ($book === null) {
            return redirect()->back()->withInput()->with('errors', [
                'loan_detail_id' => 'Buku pada peminjaman tidak ditemukan.',
            ]);
        }

        $db->table('loan_returns')->insert([
            'loan_detail_id' => $loanDetailId,
            'user_id' => (int) session()->get('user_id'),
            'quantity' => $quantity,
            'returned_at' => date('Y-m-d H:i:s'),
            'notes' => trim((string) $this->request->getPost('notes')) ?: null,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $db->table('books')
            ->where('id', (int) $loanDetail['book_id'])
            ->set('stock_available', (int) $book['stock_available'] + $quantity)
            ->update();

        return redirect()->to('/returns')->with('success', 'Pengembalian berhasil dicatat.');
    }
}
