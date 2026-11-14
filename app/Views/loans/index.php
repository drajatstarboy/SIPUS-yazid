<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Data Peminjaman</h3>
        <p class="text-muted mb-0">Kelola proses peminjaman buku.</p>
    </div>

    <a href="<?= site_url('loans/new') ?>" class="btn btn-primary">
        + Tambah Peminjaman
    </a>
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
                        <th>Kode Pinjam</th>
                        <th>Anggota</th>
                        <th>Petugas</th>
                        <th>Tanggal Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($loans)): ?>
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data peminjaman.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($loans as $i => $loan): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= esc($loan['loan_code'] ?? '-') ?></td>
                                <td><?= esc($loan['member_name'] ?? '-') ?></td>
                                <td><?= esc($loan['user_name'] ?? '-') ?></td>
                                <td><?= esc($loan['loan_date'] ?? '-') ?></td>
                                <td><?= esc($loan['due_date'] ?? '-') ?></td>
                                <td>
                                    <?php if (($loan['status'] ?? 'active') === 'active'): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Selesai</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= site_url('loans/edit/' . ($loan['id'] ?? 0)) ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <form class="d-inline" method="post" action="<?= site_url('loans/delete/' . ($loan['id'] ?? 0)) ?>" onsubmit="return confirm('Hapus data peminjaman ini?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
