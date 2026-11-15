<?php
$title = $title ?? "Victor Azmanov";
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($title) ?></title>

    <link rel="stylesheet" href="/style.css">
    <link rel="me" href="https://orcid.org/0009-0005-2574-3948">
</head>

<body>
<article>
<?php if (!empty($showSiteHeader)): ?>
<div class="site-header">
    <a href="/">
        <img src="https://samizdat.vjbe.net/2026-08-22-1787359966985.jpg" alt="Victor Azmanov" class="profile-pic">
    </a>
    <h3><a href="/" class="site-header-name">Victor Azmanov</a></h3>
</div>
<?php else: ?>
<p class="back-link"><a href="<?= htmlspecialchars($backHref ?? '/') ?>">← <?= htmlspecialchars($backLabel ?? 'Home') ?></a></p>
<?php endif; ?>
