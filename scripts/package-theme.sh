#!/bin/sh
set -eu

repo_dir=$(CDPATH= cd -- "$(dirname "$0")/.." && pwd)
theme_dir="$repo_dir/Wordpress/horn-free-theme"
dist_dir="$repo_dir/dist"
version=$(sed -n 's/^Version:[[:space:]]*//p' "$theme_dir/style.css" | head -n 1)
archive="$dist_dir/horn-free-theme-v$version.zip"

"$repo_dir/scripts/validate-theme.sh"
mkdir -p "$dist_dir"

if [ -f "$archive" ]; then
	rm "$archive"
fi

cd "$repo_dir/Wordpress"
zip -qr "$archive" horn-free-theme -x '*.DS_Store' '*/node_modules/*' '*/vendor/*'
unzip -t "$archive" >/dev/null

echo "$archive"
