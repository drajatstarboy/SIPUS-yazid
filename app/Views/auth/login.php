<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - SIPUS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">
<main class="container min-vh-100 d-flex align-items-center justify-content-center py-4">
    <div class="card border-0 shadow-sm w-100" style="max-width: 420px">
        <div class="card-body p-4">
            <h1 class="h3 fw-bold text-primary">Masuk SIPUS</h1>
            <p class="text-muted">Sistem Informasi Perpustakaan Sekolah</p>

            <?php if ($message = session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= esc($message) ?></div>
            <?php endif; ?>
            <?php if ($message = session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= esc($message) ?></div>
            <?php endif; ?>

            <form action="<?= site_url('login') ?>" method="post">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control" type="email" id="email" name="email"
                           value="<?= esc(old('email')) ?>" autocomplete="username"
                           required maxlength="150" autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Kata sandi</label>
                    <input class="form-control" type="password" id="password" name="password"
                           autocomplete="current-password" required>
                </div>
                <button class="btn btn-primary w-100" type="submit">Masuk</button>
            </form>
        </div>
    </div>
</main>
</body>
</html>
