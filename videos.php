<?php
require __DIR__ . '/lib/videos.php';

$videos = load_videos();

$tag = $_GET['tag'] ?? '';
$tagCounts = [];
foreach ($videos as $v) {
    foreach ($v['tags'] as $t) {
        $tagCounts[$t] = ($tagCounts[$t] ?? 0) + 1;
    }
}
ksort($tagCounts);
if (!isset($tagCounts[$tag])) $tag = '';

$shown = array_filter($videos, fn($v) => $tag === '' || in_array($tag, $v['tags'], true));

$title = "Videos";
include "partials/header.php";
?>
<h1><?= h($title) ?></h1>
<p class="clip-intro">Talks, educational videos & animations.</p>

<?php if ($tagCounts): ?>
<nav class="clip-filters">
    <a class="clip-pill <?= $tag === '' ? 'is-active' : '' ?>" href="<?= h(videos_url()) ?>">All</a>
    <?php foreach ($tagCounts as $t => $n): ?>
        <a class="clip-pill <?= $tag === $t ? 'is-active' : '' ?>" href="<?= h(videos_url($t)) ?>"><?= h(ucfirst($t)) ?></a>
    <?php endforeach; ?>
</nav>
<?php endif; ?>

<div class="video-feed">
    <?php foreach ($shown as $v): ?>
    <div class="video-card" id="<?= h($v['slug']) ?>">
        <?= video_player($v) ?>
        <div class="video-card-body">
            <h2 class="video-card-title"><a href="<?= h(video_url($v['slug'])) ?>"><?= h($v['title']) ?></a></h2>
            <p class="video-card-meta"><?= video_meta($v) ?></p>
            <?php if (!empty($v['description'])): ?>
                <p class="video-card-desc"><?= $v['description'] ?></p>
            <?php endif; ?>
            <?php if ($v['tags']): ?>
            <div class="clip-inline-tags video-card-tags">
                <?php foreach ($v['tags'] as $t): ?>
                    <a href="<?= h(videos_url($t)) ?>">#<?= h($t) ?></a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php if (!$shown): ?>
<p class="clip-empty">No videos yet.</p>
<?php endif; ?>

<?php include "partials/footer.php"; ?>
