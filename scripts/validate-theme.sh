#!/bin/sh
set -eu

repo_dir=$(CDPATH= cd -- "$(dirname "$0")/.." && pwd)
theme_dir="$repo_dir/Wordpress/horn-free-theme"

required_files="style.css functions.php header.php footer.php front-page.php index.php assets/css/site.css assets/js/site.js inc/acf-fields.php acf-export-horn-free-homepage.json"
for relative_path in $required_files; do
	if [ ! -s "$theme_dir/$relative_path" ]; then
		echo "Missing required theme file: $relative_path" >&2
		exit 1
	fi
done

if command -v php >/dev/null 2>&1; then
	find "$theme_dir" -type f -name '*.php' -exec php -l {} \;
else
	echo "PHP CLI not installed; PHP syntax validation skipped locally."
fi

if command -v node >/dev/null 2>&1; then
	node --check "$theme_dir/assets/js/site.js"
	node -e "const fs=require('fs');const group=JSON.parse(fs.readFileSync(process.argv[1],'utf8'));if(!Array.isArray(group)||!group[0]||group[0].key!=='group_hfi_homepage')process.exit(1)" "$theme_dir/acf-export-horn-free-homepage.json"
else
	echo "Node.js not installed; JavaScript and ACF JSON validation skipped locally."
fi

if rg -n 'thakorrahul919@gmail\.com' "$theme_dir" >/dev/null 2>&1; then
	echo "Obsolete temporary recipient found in theme." >&2
	exit 1
fi

if ! rg -n 'nitin\.gadkari@nic\.in' "$theme_dir/front-page.php" >/dev/null 2>&1; then
	echo "Verified Ministry recipient is missing from front-page.php." >&2
	exit 1
fi

echo "Theme validation completed successfully."
