<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$member = $member ?? null;
$errors = $errors ?? [];
$isEdit = $member !== null;
?>

<div class="mb-4">
    <h3><?= $isEdit ? 'Edit Anggota' : 'Tambah Anggota' ?></h3>
    <p class="text-muted">Lengkapi informasi anggota perpustakaan.</p>
</div>

<?php if (! empty($errors)): ?>
    <div class="alert alert-danger">
        <strong>Periksa kembali data berikut:</strong>
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="<?= $isEdit ? site_url('members/update/' . $member['id']) : site_url('members/create') ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label">Kode Anggota</label>
                <input type="text" name="member_code" class="form-control" maxlength="30"
                    value="<?= esc(old('member_code', $member['member_code'] ?? '')) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" maxlength="150"
                    value="<?= esc(old('name', $member['name'] ?? '')) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Kelas</label>
                <input type="text" name="class_name" class="form-control" maxlength="50"
                    value="<?= esc(old('class_name', $member['class_name'] ?? '')) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Nomor Telepon</label>
                <input type="text" name="phone" class="form-control" maxlength="30"
                    value="<?= esc(old('phone', $member['phone'] ?? '')) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="active" <?= old('status', $member['status'] ?? '') === 'active' ? 'selected' : '' ?>>Aktif</option>
                    <option value="inactive" <?= old('status', $member['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                <?= $isEdit ? 'Update Anggota' : 'Simpan Anggota' ?>
            </button>

            <a href="<?= site_url('members') ?>" class="btn btn-secondary">
                Kembali
            </a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
