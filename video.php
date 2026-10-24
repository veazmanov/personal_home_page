<?php
require __DIR__ . '/lib/videos.php';

$v = find_video($_GET['v'] ?? '');

if (!$v) {
    http_response_code(404);
    $title = "Video not found";
    include "partials/header.php";
    echo '<h1>Video not found</h1><p><a href="videos">← All videos</a></p>';
    include "partials/footer.php";
    exit;
}

$title = $v['title'];
include "partials/header.php";
?>
<p class="video-back"><a href="videos">← All videos</a></p>
<h1><?= h($v['title']) ?></h1>
<p class="video-meta"><?= video_meta($v) ?></p>

<?php if (!empty($v['description'])): ?>
    <p><?= $v['description'] ?></p>
<?php endif; ?>

<div class="video-container">
    <?= video_player($v) ?>
</div>

<?php if ($v['tags']): ?>
<div class="clip-inline-tags video-page-tags">
    <?php foreach ($v['tags'] as $t): ?>
        <a href="<?= h(videos_url($t)) ?>">#<?= h($t) ?></a>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<h2 class="video-sources-heading">Watch elsewhere</h2>
<ul class="video-sources">
    <?php foreach ($v['links'] ?? [] as $link): ?>
        <li><a href="<?= h($link['url']) ?>" target="_blank" rel="noopener"><?= h($link['label']) ?></a></li>
    <?php endforeach; ?>
    <li><a href="<?= h($v['src']) ?>">Direct download</a></li>
</ul>

<?php include "partials/footer.php"; ?>
