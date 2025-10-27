#!/bin/bash
set -euo pipefail
WP_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
JSON_FILE="$WP_ROOT/wp-content/uploads/contacts_seed_options.json"
PHP_BIN="$(command -v php || true)"
if [[ -z "$PHP_BIN" ]]; then
  echo "PHP binary not found." >&2
  exit 1
fi
"$PHP_BIN" "$WP_ROOT/scripts/seed-contact-acf.php" "$JSON_FILE"
