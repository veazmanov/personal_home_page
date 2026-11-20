
<?php
$title = "Contact";
include "partials/header.php";
?>
<div class="contact-page">
    <h1><?= htmlspecialchars($title) ?></h1>
    <ul class="contact-list">
        <li><span class="contact-label">Email</span> <a href="mailto:victor@vjbe.net">victor@vjbe.net</a></li>
        <li><span class="contact-label">Penny Post</span> <a href="https://pp.vjbe.net/vic">pp.vjbe.net/vic</a></li>
        <li><span class="contact-label">GitHub</span> <a href="https://github.com/veazmanov">github.com/veazmanov</a></li>
        <li><span class="contact-label">ORCID</span> <a href="https://orcid.org/0009-0005-2574-3948" rel="me"><?php include "partials/orcid-icon.php"; ?> https://orcid.org/0009-0005-2574-3948</a></li>
        <li><span class="contact-label">Snail mail</span> I enjoy receiving snail mail. Please send me an e-mail first to get my postal address.</li>
    </ul>
</div>
<?php include "partials/footer.php"; ?>
