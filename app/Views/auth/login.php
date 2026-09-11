<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar · Mini LMS</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>
<body>
    <main class="wrap narrow">
        <h1>Mini LMS</h1>
        <p>Plataforma de cursos com matrícula e progresso.</p>
        <?php if (session()->getFlashdata('error')): ?>
            <p class="flash err"><?= esc(session()->getFlashdata('error')) ?></p>
        <?php endif; ?>
        <form method="post" action="<?= site_url('login') ?>">
            <?= csrf_field() ?>
            <label>E-mail <input type="email" name="email" value="aluno@lms.local" required></label>
            <label>Senha <input type="password" name="password" value="Aluno@123" required></label>
            <button type="submit">Entrar</button>
        </form>
        <p class="hint">aluno@lms.local / Aluno@123 · admin@lms.local / Admin@123</p>
    </main>
</body>
</html>
