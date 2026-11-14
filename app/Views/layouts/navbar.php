<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">

        <a class="navbar-brand sipus-brand"
           href="<?= site_url('/') ?>">
            SIPUS
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#sipusNavbar"
                aria-controls="sipusNavbar"
                aria-expanded="false"
                aria-label="Buka navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="sipusNavbar">

            <div class="navbar-nav ms-auto align-items-lg-center">
                <span class="navbar-text text-white me-lg-3">
                    <?= esc(session()->get('user_name')) ?>
                    <span class="badge bg-light text-primary">
                        <?= esc(session()->get('user_role') === 'admin' ? 'Admin' : 'Petugas') ?>
                    </span>
                </span>
                <a class="nav-link"
                   href="<?= site_url('categories') ?>">
                    Kategori
                </a>

                <a class="nav-link"
                   href="<?= site_url('books') ?>">
                    Data Buku
                </a>
                <form action="<?= site_url('logout') ?>" method="post" class="d-flex ms-lg-3">
                    <?= csrf_field() ?>
                    <button class="btn btn-outline-light btn-sm" type="submit">Keluar</button>
                </form>
            </div>

        </div>
    </div>
</nav>