<?php

namespace App\Models;

use CodeIgniter\Model;

class BookModel extends Model
{
    protected $table            = 'books';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'category_id',
        'code',
        'title',
        'author',
        'publisher',
        'published_year',
        'stock_total',
        'stock_available',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';

    /**
     * Mengambil daftar buku beserta nama kategorinya.
     */
    public function getBooksWithCategory(
        ?string $search = null,
        ?int $categoryId = null,
        bool $activeOnly = false,
        string $availability = ''
    )
    {
        $builder = $this->select('books.*, categories.name AS category_name')
            ->join('categories', 'categories.id = books.category_id');

        if ($search !== null && $search !== '') {
            $builder->groupStart()
                ->like('books.title', $search)
                ->orLike('books.code', $search)
                ->orLike('books.author', $search)
                ->groupEnd();
        }

        if ($categoryId !== null) {
            $builder->where('books.category_id', $categoryId);
        }

        if ($activeOnly) {
            $builder->where('books.is_active', 1);
        }

        if ($availability === 'available') {
            $builder->where('books.stock_available >', 0);
        } elseif ($availability === 'unavailable') {
            $builder->where('books.stock_available', 0);
        }

        return $builder
            ->orderBy('books.title', 'ASC')
            ->findAll();
    }

    /**
     * Mengambil jumlah koleksi dan total eksemplar yang tersedia.
     */
    public function getCatalogSummary(): array
    {
        $summary = db_connect()->table($this->table)
            ->select('COUNT(*) AS total_books, COALESCE(SUM(stock_available), 0) AS available_books')
            ->get()
            ->getRowArray();

        return [
            'total_books'     => (int) $summary['total_books'],
            'available_books' => (int) $summary['available_books'],
        ];
    }
}