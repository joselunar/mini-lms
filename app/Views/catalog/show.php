<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<h1><?= esc($course['title']) ?></h1>
<p><?= esc($course['description']) ?></p>
<p>Status: <strong><?= esc($course['status']) ?></strong> · <?= (int) $course['workload_hours'] ?> horas</p>

<?php if ($course['enrollment'] === null): ?>
    <form method="post" action="<?= site_url('cursos/' . $course['id'] . '/matricular') ?>">
        <?= csrf_field() ?>
        <button type="submit">Matricular-se</button>
    </form>
<?php else: ?>
    <p>Progresso: <?= (int) $course['enrollment']['progress_percent'] ?>% · <?= esc($course['enrollment']['status']) ?></p>
<?php endif; ?>

<h2>Aulas</h2>
<ol>
    <?php foreach ($course['lessons'] as $lesson): ?>
        <li>
            <?php if ($course['enrollment']): ?>
                <a href="<?= site_url('cursos/' . $course['id'] . '/aulas/' . $lesson['id']) ?>"><?= esc($lesson['title']) ?></a>
            <?php else: ?>
                <?= esc($lesson['title']) ?>
            <?php endif; ?>
            <?php if (! empty($lesson['completed'])): ?> · concluída<?php endif; ?>
        </li>
    <?php endforeach; ?>
</ol>
<?= $this->endSection() ?>
