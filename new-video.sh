#!/usr/bin/env bash
# Add a video to data/videos/<slug>.json.
# Usage: ./new-video.sh [video-url]

set -euo pipefail

cd "$(dirname "$0")"
DIR="data/videos"

if ! command -v jq >/dev/null; then
    echo "This script needs jq: brew install jq" >&2
    exit 1
fi

mkdir -p "$DIR"

ask() {   # ask "Prompt" [default]
    local answer
    if [ -n "${2:-}" ]; then
        read -r -p "$1 [$2]: " answer
        echo "${answer:-$2}"
    else
        read -r -p "$1: " answer
        echo "$answer"
    fi
}

# --- Video URL (required) ---
src="${1:-}"
while [ -z "$src" ]; do src=$(ask "Video URL (e.g. https://video.vjbe.net/2026-10-24-talk.mp4)"); done

if grep -rqF "\"$src\"" "$DIR" 2>/dev/null; then
    echo "⚠️  That video URL is already used in $DIR."
    [ "$(ask "Add it anyway? (y/n)" n)" = "y" ] || exit 0
fi

# --- Title (required) ---
title=""
while [ -z "$title" ]; do title=$(ask "Title"); done

# --- Date: default to the date in the filename, else today ---
guess=$(basename "$src" | grep -oE '^[0-9]{4}-[0-9]{2}-[0-9]{2}' || true)
date=$(ask "Date (YYYY-MM-DD, YYYY-MM or YYYY)" "${guess:-$(date +%Y-%m-%d)}")

# --- Optional fields ---
existing_tags=$(jq -rs '[.[].tags[]?] | unique | join(", ")' "$DIR"/*.json 2>/dev/null || true)
[ -n "$existing_tags" ] && echo "Existing tags: $existing_tags"
tags=$(ask "Tags, comma-separated" talk)
note=$(ask "Short note next to the date (optional)")
description=$(ask "Description (optional, HTML allowed)")

# --- Slug: date + title, lowercase, dashes ---
title_slug=$(echo "$title" | iconv -f utf-8 -t ascii//TRANSLIT 2>/dev/null | tr '[:upper:]' '[:lower:]' \
    | sed -E 's/[^a-z0-9]+/-/g; s/^-+|-+$//g' | cut -c1-50 | sed -E 's/-+$//')
slug=$(ask "Slug (used in the URL)" "${date}-${title_slug}")
file="$DIR/$slug.json"

if [ -e "$file" ]; then
    echo "❌ $file already exists." >&2
    exit 1
fi

# --- Build the entry (empty fields are dropped) ---
entry=$(jq -n \
    --arg title "$title" --arg date "$date" --arg src "$src" \
    --arg tags "$tags" --arg note "$note" --arg description "$description" '
    {
        title: $title,
        date: $date,
        tags: ($tags | split(",") | map(gsub("^\\s+|\\s+$"; "") | ascii_downcase) | map(select(. != ""))),
        note: $note,
        description: $description,
        src: $src
    }
    | with_entries(select(.value != "" and .value != []))
')

echo
echo "$entry"
echo
[ "$(ask "Save as $file? (y/n)" y)" = "y" ] || { echo "Cancelled."; exit 0; }

echo "$entry" > "$file"
echo "✅ Saved $file — view it at video.php?v=$slug"

if [ -x ./deploy.sh ] && [ "$(ask "Deploy now? (y/n)" n)" = "y" ]; then
    ./deploy.sh
fi
