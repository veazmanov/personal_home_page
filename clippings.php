<?php
$title = "Web Clippings";
include "partials/header.php";

$clippings = json_decode(file_get_contents(__DIR__ . '/data/clippings.json'), true) ?? [];
$types = ['text' => 'Text', 'audio' => 'Audio', 'video' => 'Video'];

$type = $_GET['type'] ?? '';
$tag  = $_GET['tag'] ?? '';
if (!isset($types[$type])) $type = '';

// Count tags among clippings of the current type
$tagCounts = [];
foreach ($clippings as $c) {
    if ($type && $c['type'] !== $type) continue;
    foreach ($c['tags'] ?? [] as $t) {
        $tagCounts[$t] = ($tagCounts[$t] ?? 0) + 1;
    }
}
ksort($tagCounts);

$shown = array_filter($clippings, function ($c) use ($type, $tag) {
    return (!$type || $c['type'] === $type)
        && (!$tag || in_array($tag, $c['tags'] ?? [], true));
});

function clip_url(array $params): string {
    $params = array_filter($params, fn($v) => $v !== '' && $v !== null);
    return 'clippings.php' . ($params ? '?' . http_build_query($params) : '');
}

function clip_domain(string $url): string {
    $host = parse_url($url, PHP_URL_HOST) ?? '';
    return preg_replace('/^www\./', '', $host);
}

function h(?string $s): string {
    return htmlspecialchars($s ?? '', ENT_QUOTES);
}
?>
<h1><?= h($title) ?></h1>
<p class="clip-intro">Cool stuff I've found on the 'net.</p>

<nav class="clip-filters">
    <a class="clip-pill <?= $type === '' ? 'is-active' : '' ?>" href="<?= h(clip_url(['tag' => $tag])) ?>">All</a>
    <?php foreach ($types as $key => $label): ?>
        <a class="clip-pill <?= $type === $key ? 'is-active' : '' ?>" href="<?= h(clip_url(['type' => $key, 'tag' => $tag])) ?>"><?= h($label) ?></a>
    <?php endforeach; ?>
</nav>

<?php if ($tagCounts): ?>
<nav class="clip-tags">
    <?php foreach ($tagCounts as $t => $n): ?>
        <a class="clip-tag <?= $tag === $t ? 'is-active' : '' ?>"
           href="<?= h(clip_url(['type' => $type, 'tag' => $tag === $t ? '' : $t])) ?>">#<?= h($t) ?> <span><?= $n ?></span></a>
    <?php endforeach; ?>
</nav>
<?php endif; ?>

<?php if ($tag): ?>
<p class="clip-active-filter">Tagged <strong>#<?= h($tag) ?></strong> · <a href="<?= h(clip_url(['type' => $type])) ?>">clear</a></p>
<?php endif; ?>

<?php if (!$shown): ?>
<p class="clip-empty">Nothing here yet.</p>
<?php else: ?>
<ul class="clip-list">
    <?php foreach ($shown as $c): ?>
    <li class="clip">
        <div class="clip-head">
            <span class="clip-type"><?= h($types[$c['type']] ?? $c['type']) ?></span>
            <a class="clip-title" href="<?= h($c['url']) ?>" target="_blank" rel="noopener"><?= h($c['title']) ?></a>
        </div>
        <div class="clip-meta">
            <?php
            $meta = array_filter([
                !empty($c['author']) ? $c['author'] : null,
                clip_domain($c['url']),
                !empty($c['added']) ? date('M Y', strtotime($c['added'])) : null,
            ]);
            echo h(implode(' · ', $meta));
            ?>
        </div>
        <?php if (!empty($c['note'])): ?>
            <p class="clip-note"><?= h($c['note']) ?></p>
        <?php endif; ?>
        <?php if (!empty($c['tags'])): ?>
        <div class="clip-inline-tags">
            <?php foreach ($c['tags'] as $t): ?>
                <a href="<?= h(clip_url(['type' => $type, 'tag' => $t])) ?>">#<?= h($t) ?></a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

<?php include "partials/footer.php"; ?>
