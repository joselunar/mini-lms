<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<h1>Catálogo</h1>
<p>Somente cursos publicados aparecem aqui.</p>
<ul class="list">
    <?php foreach ($courses as $course): ?>
        <li>
            <a href="<?= site_url('cursos/' . $course['id']) ?>"><strong><?= esc($course['title']) ?></strong></a>
            <span><?= (int) $course['workload_hours'] ?>h · <?= (int) $course['lessons_count'] ?> aulas · <?= esc($course['status']) ?></span>
        </li>
    <?php endforeach; ?>
</ul>
<?= $this->endSection() ?>
