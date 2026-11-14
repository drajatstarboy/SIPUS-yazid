<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Data Anggota</h3>
        <p class="text-muted mb-0">Kelola data anggota perpustakaan.</p>
    </div>

    <?php if (session()->get('user_role') === 'admin'): ?>
        <a href="<?= site_url('members/new') ?>" class="btn btn-primary">
            + Tambah Anggota
        </a>
    <?php endif; ?>
</div>

<?php if ($message = session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= esc($message) ?>
    </div>
<?php endif; ?>

<?php if ($message = session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= esc($message) ?>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>No.</th>
                        <th>Kode Anggota</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Telepon</th>
                        <th>Status</th>
                        <?php if (session()->get('user_role') === 'admin'): ?>
                            <th>Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($members)): ?>
                        <tr>
                            <td colspan="<?= session()->get('user_role') === 'admin' ? 7 : 6 ?>" class="text-center">
                                Belum ada data anggota.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($members as $i => $member): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= esc($member['member_code']) ?></td>
                                <td><?= esc($member['name']) ?></td>
                                <td><?= esc($member['class_name'] ?? '-') ?></td>
                                <td><?= esc($member['phone'] ?? '-') ?></td>
                                <td>
                                    <?php if ($member['status'] === 'active'): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <?php if (session()->get('user_role') === 'admin'): ?>
                                    <td>
                                        <a href="<?= site_url('members/edit/' . $member['id']) ?>" class="btn btn-sm btn-warning">
                                            Edit
                                        </a>

                                        <form class="d-inline" method="post" action="<?= site_url('members/delete/' . $member['id']) ?>" onsubmit="return confirm('Hapus data anggota ini?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
