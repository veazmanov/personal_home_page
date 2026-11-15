#!/usr/bin/env bash
# Local dev server: first free port from 8012 up, opened in Firefox.
# Uses phpserve from ~/bin (dotfiles); falls back to plain php -S without it.
cd "$(dirname "$0")"

if command -v phpserve >/dev/null; then
    PHPSERVE_PORT=8012 PHPSERVE_BROWSER=Firefox exec phpserve lib/dev-router.php
else
    exec php -S localhost:8012 lib/dev-router.php
fi
