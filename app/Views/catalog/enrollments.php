<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<h1>Minhas matrículas</h1>
<ul class="list">
    <?php foreach ($enrollments as $item): ?>
        <li>
            <a href="<?= site_url('cursos/' . $item['course_id']) ?>"><strong><?= esc($item['course_title']) ?></strong></a>
            <span><?= esc($item['status']) ?> · <?= (int) $item['progress_percent'] ?>%</span>
        </li>
    <?php endforeach; ?>
    <?php if ($enrollments === []): ?>
        <li>Nenhuma matrícula ainda.</li>
    <?php endif; ?>
</ul>
<?= $this->endSection() ?>
