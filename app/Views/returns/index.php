<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Pengembalian Buku</h3>
        <p class="text-muted mb-0">Catat buku yang dikembalikan ke perpustakaan.</p>
    </div>
</div>

<?php if ($message = session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc($message) ?></div>
<?php endif; ?>

<?php if ($message = session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc($message) ?></div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>No.</th>
                        <th>Judul Buku</th>
                        <th>Anggota</th>
                        <th>Petugas</th>
                        <th>Jumlah</th>
                        <th>Tanggal Kembali</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($returns)): ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data pengembalian.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($returns as $i => $row): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= esc($row['book_title'] ?? '-') ?></td>
                                <td><?= esc($row['member_name'] ?? '-') ?></td>
                                <td><?= esc($row['user_name'] ?? '-') ?></td>
                                <td><?= esc($row['quantity'] ?? 0) ?></td>
                                <td><?= esc($row['returned_at'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
