<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Mini LMS') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>
<body>
    <header class="top">
        <a href="<?= site_url('/') ?>" class="logo">Mini LMS</a>
        <nav>
            <a href="<?= site_url('/') ?>">Cursos</a>
            <a href="<?= site_url('matriculas') ?>">Matrículas</a>
            <span><?= esc(session('user_name')) ?></span>
            <a href="<?= site_url('logout') ?>">Sair</a>
        </nav>
    </header>
    <main class="wrap">
        <?php if (session()->getFlashdata('success')): ?>
            <p class="flash ok"><?= esc(session()->getFlashdata('success')) ?></p>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <p class="flash err"><?= esc(session()->getFlashdata('error')) ?></p>
        <?php endif; ?>
        <?= $this->renderSection('content') ?>
    </main>
</body>
</html>
