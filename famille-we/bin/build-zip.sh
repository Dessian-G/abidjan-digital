#!/usr/bin/env bash
# Construit famille-we/dist/famille-we.zip, à téléverser dans WordPress
# (Extensions → Ajouter → Téléverser une extension).
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
WORK="$(mktemp -d)"
trap 'rm -rf "$WORK"' EXIT

# Le dossier dans le zip doit s'appeler « famille-we » (identifiant du plugin).
mkdir -p "$WORK/famille-we" "$ROOT/dist"
cp -R "$ROOT/plugin/." "$WORK/famille-we/"

# Vérifie la syntaxe PHP avant d'empaqueter.
if command -v php >/dev/null 2>&1; then
	find "$WORK/famille-we" -name '*.php' -print0 | xargs -0 -n1 php -l >/dev/null
fi

rm -f "$ROOT/dist/famille-we.zip"
(cd "$WORK" && zip -qr "$ROOT/dist/famille-we.zip" famille-we -x '*.DS_Store')
echo "Créé : $ROOT/dist/famille-we.zip"
