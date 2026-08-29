#!/usr/bin/env bash
# Run once per clone to enable the pre-commit hook (.githooks/pre-commit).
# Uses `git config core.hooksPath` instead of copying into .git/hooks — the
# hook then lives in the repo, versioned, and updates for everyone on pull.
set -euo pipefail
cd "$(dirname "$0")/.."

chmod +x .githooks/pre-commit
git config core.hooksPath .githooks

echo "Pre-commit hook enabled (runs Pint on staged files before each commit)."
echo "Bypass once with: git commit --no-verify"
