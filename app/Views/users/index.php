<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <h1 class="h3 mb-1">Pengguna dan Role</h1>
    <p class="text-muted mb-0">Buat akun admin atau petugas untuk masuk ke SIPUS.</p>
</div>

<?php if ($message = session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc($message) ?></div>
<?php endif; ?>
<?php if ($message = session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc($message) ?></div>
<?php endif; ?>
<?php if (! empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h2 class="h5">Tambah pengguna</h2>
        <form action="<?= site_url('users/create') ?>" method="post" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-3">
                <label class="form-label" for="name">Nama</label>
                <input class="form-control" id="name" name="name" value="<?= esc(old('name')) ?>"
                       required maxlength="100">
            </div>
            <div class="col-md-3">
                <label class="form-label" for="email">Email</label>
                <input class="form-control" type="email" id="email" name="email"
                       value="<?= esc(old('email')) ?>" required maxlength="150">
            </div>
            <div class="col-md-3">
                <label class="form-label" for="password">Kata sandi (minimal 12 karakter)</label>
                <input class="form-control" type="password" id="password" name="password"
                       required minlength="12" maxlength="255" autocomplete="new-password">
            </div>
            <div class="col-md-2">
                <label class="form-label" for="role">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="petugas" <?= old('role') === 'petugas' ? 'selected' : '' ?>>Petugas</option>
                    <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button class="btn btn-primary w-100" type="submit">Tambah</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= esc($user['name']) ?></td>
                        <td><?= esc($user['email']) ?></td>
                        <td><span class="badge <?= $user['role'] === 'admin' ? 'bg-primary' : 'bg-secondary' ?>">
                            <?= esc(ucfirst($user['role'])) ?>
                        </span></td>
                        <td><?= (int) $user['is_active'] === 1 ? 'Aktif' : 'Nonaktif' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
