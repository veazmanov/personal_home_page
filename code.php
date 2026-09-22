<?php
$title = "Code";
include "partials/header.php";
?>
    <h1><?= htmlspecialchars($title) ?></h1>

<h2>Coding Projects</h2>
<section class="project-grid">
    <a class="project-tile" href="https://pennypost.vjbe.net">
        <h2>Penny Post</h2>
        <p>Write letters.</p>
    </a>
    <a class="project-tile" href="https://bookmarks.vjbe.net">
        <h2>Bookmarks</h2>
        <p>Save bookmarks</p>
    </a>
    <a class="project-tile" href="https://oldnews.vjbe.net">
        <h2>Old News</h2>
        <p>Read newspaper articles in plaintext</p>
    </a>
    <a class="project-tile" href="https://on2.vjbe.net">
        <h2>On2 Salsa Practice</h2>
        <p>Practice on-2 salsa</p>
    </a>
    <a class="project-tile" href="https://invertlogarithmicpowerseries-btifvhzwbwwcgt6zvpw8r2.streamlit.app/">
        <h2>Inverting Power Series</h2>
        <p>A Streamlit app for inverting the power series of ln(1+x).</p>
    </a>
</section>

<h2 class="project-section-heading">Other</h2>
<section class="project-grid">
    <a class="project-tile" href="https://recipes.vjbe.net">
        <h2>Victor's Recipes</h2>
        <p>Simple recipes</p>
    </a>
    <a class="project-tile" href="https://ahmed-awad-photo.vjbe.net/">
        <h2>Ahmed Abu Awad's Photo Portfolio</h2>
        <p>A photography portfolio site.</p>
    </a>
    <a href="https://azmanov.vjbe.net/">
        My grandfather's personal website for his sculptures. Maintained backup of azmanov.com
    </a>
</section>
<?php include "partials/footer.php"; ?>

