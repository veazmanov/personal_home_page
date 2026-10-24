<?php
// Router for `php -S` (see dev.sh), mirroring the Caddy config in production:
// /foo serves foo.php, and /foo.php redirects to /foo.
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$root = dirname(__DIR__);

if (preg_match('#^/(.+)\.php$#', $path, $m) && $m[1] !== 'index' && is_file("$root/$path")) {
    $query = $_SERVER['QUERY_STRING'] ?? '';
    header('Location: /' . $m[1] . ($query !== '' ? "?$query" : ''), true, 301);
    return true;
}

if ($path !== '/' && is_file("$root/$path.php")) {
    $_SERVER['SCRIPT_NAME'] = "$path.php";
    chdir($root);
    require "$root/$path.php";
    return true;
}

return false;
