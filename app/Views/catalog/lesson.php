<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<p><a href="<?= site_url('cursos/' . $course['id']) ?>">← <?= esc($course['title']) ?></a></p>
<h1><?= esc($lesson['title']) ?></h1>
<p><?= nl2br(esc($lesson['content'])) ?></p>
<p><?= (int) $lesson['duration_minutes'] ?> minutos</p>

<?php if (empty($lesson['completed'])): ?>
    <form method="post" action="<?= site_url('cursos/' . $course['id'] . '/aulas/' . $lesson['id'] . '/concluir') ?>">
        <?= csrf_field() ?>
        <button type="submit">Concluir aula</button>
    </form>
<?php else: ?>
    <p class="flash ok">Aula já concluída.</p>
<?php endif; ?>
<?= $this->endSection() ?>
