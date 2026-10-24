<?php
// Videos live in data/videos/<slug>.json, one file per video.
//
// Fields:
//   title        (required)
//   src          (required) URL of the video file
//   date         "YYYY-MM-DD", "YYYY-MM" or "YYYY" — newest first
//   tags         ["talk", ...]
//   note         short line shown next to the date (HTML allowed)
//   description  paragraph(s) under the video (HTML allowed)
//   links        [{ "label": "YouTube", "url": "..." }]
//
// The filename (minus .json) is the video's slug: video.php?v=<slug>.
// HTML in note/description is output as-is, so only put your own markup there.

function load_videos(): array
{
    $videos = [];
    foreach (glob(__DIR__ . '/../data/videos/*.json') as $file) {
        $v = json_decode(file_get_contents($file), true);
        if (!is_array($v) || empty($v['title']) || empty($v['src'])) {
            continue; // skip broken files rather than break the page
        }
        $v['slug'] = basename($file, '.json');
        $v['tags'] = $v['tags'] ?? [];
        $v['date'] = (string)($v['date'] ?? '');
        $videos[] = $v;
    }

    usort($videos, fn($a, $b) => [$b['date'], $b['slug']] <=> [$a['date'], $a['slug']]);
    return $videos;
}

function find_video(string $slug): ?array
{
    foreach (load_videos() as $v) {
        if ($v['slug'] === $slug) return $v;
    }
    return null;
}

function video_date(string $date): string
{
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) return date('j F Y', strtotime($date));
    if (preg_match('/^\d{4}-\d{2}$/', $date))       return date('F Y', strtotime("$date-01"));
    return $date;
}

function video_url(string $slug): string
{
    return 'video.php?v=' . rawurlencode($slug);
}

function videos_url(string $tag = ''): string
{
    return 'videos.php' . ($tag !== '' ? '?tag=' . rawurlencode($tag) : '');
}

if (!function_exists('h')) {
    function h(?string $s): string
    {
        return htmlspecialchars($s ?? '', ENT_QUOTES);
    }
}

// The <video> element plus its fallback. #t=0.1 makes Safari show the
// first frame as a thumbnail instead of a black box.
function video_player(array $v): string
{
    $src = h($v['src']);
    return <<<HTML
    <video class="video-player" controls preload="metadata" playsinline src="{$src}#t=0.1">
        Your browser can't play this video. <a href="{$src}">Download it instead</a>.
    </video>
    HTML;
}

function video_meta(array $v): string
{
    $parts = array_filter([h(video_date($v['date'])), $v['note'] ?? '']);
    return implode(' · ', $parts);
}
