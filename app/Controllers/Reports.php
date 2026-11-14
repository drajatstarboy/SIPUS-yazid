<?php

namespace App\Controllers;

class Reports extends BaseController
{
    public function index()
    {
        $db = db_connect();

        $totalLoans = $db->table('loans')->countAllResults();
        $activeLoans = $db->table('loans')->where('status', 'active')->countAllResults();
        $returnedBooks = $db->table('loan_returns')->countAllResults();
        $totalMembers = $db->table('members')->countAllResults();

        return view('reports/index', [
            'title' => 'Laporan Perpustakaan',
            'stats' => [
                'total_loans' => $totalLoans,
                'active_loans' => $activeLoans,
                'returned_books' => $returnedBooks,
                'total_members' => $totalMembers,
            ],
        ]);
    }
}
