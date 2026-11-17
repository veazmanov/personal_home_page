<?php
$title = "Campaigns";
include "partials/header.php";
?>
    <h1><?= htmlspecialchars($title) ?></h1>

<p><em>Roll up your sleeves, there's work for everybody.</em><br>
<span class="attribution">— Gianni Rodari, “Universal History”, in <cite>Favole al telefono</cite> (1962)</span></p>

<section class="project-grid">
    <a class="project-tile" href="https://agoodidea.vjbe.net">
        <h2>A Good ID(ea)</h2>
        <p>Campaign to re-introduce a physical student card at the UU.</p>
    </a>
    <a class="project-tile" href="https://plovdiv-transit.azmanov.net/">
        <h2>Plovdiv Transit</h2>
        <p>Pushing for better public transport in Plovdiv, starting with open timetables and bus GPS data.</p>
    </a>
</section>
<?php include "partials/footer.php"; ?>
