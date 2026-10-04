#!/usr/bin/env bash
# Add a new clipping to data/clippings.json (newest first).
# Usage: ./clip.sh [url]

set -euo pipefail

cd "$(dirname "$0")"
FILE="data/clippings.json"

if ! command -v jq >/dev/null; then
    echo "This script needs jq: brew install jq" >&2
    exit 1
fi

[ -f "$FILE" ] || { mkdir -p data; echo "[]" > "$FILE"; }

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

# --- URL (required) ---
url="${1:-}"
while [ -z "$url" ]; do url=$(ask "URL"); done

if jq -e --arg u "$url" 'any(.[]; .url == $u)' "$FILE" >/dev/null; then
    echo "⚠️  That URL is already in $FILE."
    [ "$(ask "Add it anyway? (y/n)" n)" = "y" ] || exit 0
fi

# --- Title (required) ---
title=""
while [ -z "$title" ]; do title=$(ask "Title"); done

# --- Type (text / audio / video) ---
type=""
while true; do
    type=$(ask "Type (t)ext / (a)udio / (v)ideo" text)
    case "$type" in
        t|text)  type=text;  break ;;
        a|audio) type=audio; break ;;
        v|video) type=video; break ;;
        *) echo "Please choose text, audio or video." ;;
    esac
done

# --- Optional fields ---
author=$(ask "Author (optional)")
note=$(ask "Note (optional)")

existing_tags=$(jq -r '[.[].tags[]?] | unique | join(", ")' "$FILE")
[ -n "$existing_tags" ] && echo "Existing tags: $existing_tags"
tags=$(ask "Tags, comma-separated (optional)")

added=$(ask "Date added" "$(date +%Y-%m-%d)")

# --- Build the entry (empty fields are dropped) ---
entry=$(jq -n \
    --arg title "$title" --arg url "$url" --arg type "$type" \
    --arg author "$author" --arg note "$note" --arg tags "$tags" --arg added "$added" '
    {
        title: $title,
        url: $url,
        type: $type,
        author: $author,
        note: $note,
        tags: ($tags | split(",") | map(gsub("^\\s+|\\s+$"; "") | ascii_downcase) | map(select(. != ""))),
        added: $added
    }
    | with_entries(select(.value != "" and .value != []))
')

echo
echo "$entry"
echo
[ "$(ask "Save this clipping? (y/n)" y)" = "y" ] || { echo "Cancelled."; exit 0; }

tmp=$(mktemp)
jq --argjson e "$entry" '[$e] + .' "$FILE" > "$tmp" && mv "$tmp" "$FILE"
echo "✅ Saved to $FILE ($(jq length "$FILE") clippings)."

if [ -x ./deploy.sh ] && [ "$(ask "Deploy now? (y/n)" n)" = "y" ]; then
    ./deploy.sh
fi
