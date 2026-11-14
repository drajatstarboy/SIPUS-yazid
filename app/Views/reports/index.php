<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <h3>Laporan Perpustakaan</h3>
    <p class="text-muted mb-0">Ringkasan kegiatan peminjaman dan pengembalian.</p>
</div>

<div class="row g-3">
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted mb-2">Total Peminjaman</div>
                <h2 class="fw-bold text-primary"><?= number_format($stats['total_loans'] ?? 0) ?></h2>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted mb-2">Peminjaman Aktif</div>
                <h2 class="fw-bold text-success"><?= number_format($stats['active_loans'] ?? 0) ?></h2>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted mb-2">Buku Dikembalikan</div>
                <h2 class="fw-bold text-warning"><?= number_format($stats['returned_books'] ?? 0) ?></h2>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted mb-2">Total Anggota</div>
                <h2 class="fw-bold text-danger"><?= number_format($stats['total_members'] ?? 0) ?></h2>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
