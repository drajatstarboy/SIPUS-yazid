<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Data Buku</h3>
        <p class="text-muted mb-0">Kelola koleksi buku perpustakaan.</p>
    </div>

    <?php if (session()->get('user_role') === 'admin'): ?>
        <a href="<?= site_url('books/new') ?>" class="btn btn-primary">
            Tambah Buku
        </a>
    <?php endif; ?>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted">Jumlah seluruh judul buku</div>
                <div class="fs-3 fw-semibold"><?= esc($summary['total_books']) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted">Jumlah eksemplar tersedia</div>
                <div class="fs-3 fw-semibold"><?= esc($summary['available_books']) ?></div>
            </div>
        </div>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= esc(session()->getFlashdata('success')) ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= esc(session()->getFlashdata('error')) ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="<?= site_url('books') ?>" method="get" class="row g-3 align-items-end mb-4">
            <div class="col-md-3">
                <label for="q" class="form-label">Cari buku</label>
                <input type="search" class="form-control" id="q" name="q"
                       value="<?= esc($filters['q']) ?>" placeholder="Kode, judul, atau penulis">
            </div>
            <div class="col-md-3">
                <label for="category_id" class="form-label">Kategori</label>
                <select class="form-select" id="category_id" name="category_id">
                    <option value="">Semua kategori</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= esc($category['id']) ?>"
                            <?= (int) $filters['category_id'] === (int) $category['id'] ? 'selected' : '' ?>>
                            <?= esc($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="active" name="active" value="1"
                        <?= $filters['active'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="active">Buku aktif saja</label>
                </div>
            </div>
            <div class="col-md-2">
                <label for="availability" class="form-label">Ketersediaan</label>
                <select class="form-select" id="availability" name="availability">
                    <option value="">Semua stok</option>
                    <option value="available" <?= $filters['availability'] === 'available' ? 'selected' : '' ?>>
                        Stok tersedia
                    </option>
                    <option value="unavailable" <?= $filters['availability'] === 'unavailable' ? 'selected' : '' ?>>
                        Stok habis
                    </option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Terapkan</button>
                <a href="<?= site_url('books') ?>" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>

        <div class="alert alert-info py-2">
            Buku yang sudah memiliki riwayat transaksi tidak dapat dihapus agar catatan peminjaman dan pengembalian tetap utuh.
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Kode</th>
                        <th>Judul Buku</th>
                        <th>Kategori</th>
                        <th>Penulis</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($books)): ?>
                        <?php $no = 1; ?>

                        <?php foreach ($books as $book): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($book['code']) ?></td>
                                <td><?= esc($book['title']) ?></td>
                                <td><?= esc($book['category_name']) ?></td>
                                <td><?= esc($book['author']) ?></td>
                                <td>
                                    <?= esc($book['stock_available']) ?>
                                    /
                                    <?= esc($book['stock_total']) ?>
                                </td>
                                <td>
                                    <?php if ($book['is_active']): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (session()->get('user_role') === 'admin'): ?>
                                        <a href="<?= site_url('books/edit/' . $book['id']) ?>"
                                           class="btn btn-sm btn-warning">
                                            Edit
                                        </a>

                                        <form action="<?= site_url('books/delete/' . $book['id']) ?>"
                                              method="post"
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus buku ini?')">

                                            <?= csrf_field() ?>

                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Hapus
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted">Hanya lihat</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">
                                <?= $filters['q'] !== '' || $filters['category_id'] !== null || $filters['active'] || $filters['availability'] !== ''
                                    ? 'Tidak ada buku yang sesuai dengan filter.'
                                    : 'Belum ada data buku.' ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
