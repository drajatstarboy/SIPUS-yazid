<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3><?= empty($loan) ? 'Tambah Peminjaman' : 'Edit Peminjaman' ?></h3>
                <p class="text-muted mb-0">Catat peminjaman buku baru atau ubah data lama.</p>
            </div>
            <a href="<?= site_url('loans') ?>" class="btn btn-outline-secondary">Kembali</a>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $field => $message): ?>
                        <li><strong><?= esc($field) ?></strong>: <?= esc($message) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php $loan = $loan ?? []; ?>
        <form method="post" action="<?= empty($loan) ? site_url('loans/create') : site_url('loans/update/' . $loan['id']) ?>">
            <?= csrf_field() ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="member_id" class="form-label">Anggota</label>
                    <select class="form-select" id="member_id" name="member_id" required>
                        <option value="">Pilih anggota</option>
                        <?php foreach ($members as $member): ?>
                            <option value="<?= esc($member['id']) ?>" <?= (string) old('member_id', $loan['member_id'] ?? '') === (string) $member['id'] ? 'selected' : '' ?>>
                                <?= esc($member['member_code']) ?> - <?= esc($member['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="book_id" class="form-label">Buku</label>
                    <select class="form-select" id="book_id" name="book_id" required>
                        <option value="">Pilih buku</option>
                        <?php foreach ($books as $book): ?>
                            <option value="<?= esc($book['id']) ?>" <?= (string) old('book_id', $loan['book_id'] ?? '') === (string) $book['id'] ? 'selected' : '' ?>>
                                <?= esc($book['code']) ?> - <?= esc($book['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="loan_date" class="form-label">Tanggal Pinjam</label>
                    <input type="date" class="form-control" id="loan_date" name="loan_date" value="<?= esc(old('loan_date', $loan['loan_date'] ?? date('Y-m-d'))) ?>" required>
                </div>

                <div class="col-md-4">
                    <label for="due_date" class="form-label">Jatuh Tempo</label>
                    <input type="date" class="form-control" id="due_date" name="due_date" value="<?= esc(old('due_date', $loan['due_date'] ?? date('Y-m-d', strtotime('+7 days')))) ?>" required>
                </div>

                <div class="col-md-4">
                    <label for="quantity" class="form-label">Jumlah</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="<?= esc(old('quantity', $loan['quantity'] ?? 1)) ?>" required>
                </div>

                <div class="col-12">
                    <label for="notes" class="form-label">Catatan</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"><?= esc(old('notes', $loan['notes'] ?? '')) ?></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><?= empty($loan) ? 'Simpan Peminjaman' : 'Perbarui Peminjaman' ?></button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
