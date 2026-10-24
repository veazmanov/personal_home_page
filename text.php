
<?php
$title = "Text";
include "partials/header.php";
?>
<h1><?= htmlspecialchars($title) ?></h1>

<section class="project-grid">
    <a class="project-tile" href="https://blog.vjbe.net/">
        <h2>Blog</h2>
        <p>Posts and talk write-ups (WordPress).</p>
    </a>
    <a class="project-tile" href="https://words.vjbe.net/">
        <h2>Words</h2>
        <p>Short essays and opinion pieces.</p>
    </a>
    <a class="project-tile" href="writing">
        <h2>Essays</h2>
        <p>History of science, mathematics.</p>
    </a>
</section>

<?php include "partials/footer.php"; ?>
