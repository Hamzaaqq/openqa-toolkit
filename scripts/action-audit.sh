#!/usr/bin/env bash
set -euo pipefail
case "$OPENQA_FAIL" in
  true|false) ;;
  *) echo 'fail-on-findings must be true or false' >&2; exit 2 ;;
esac
if [[ -z "$OPENQA_FILE" ]]; then
  echo 'An HTML file is required' >&2
  exit 2
fi
report="$(mktemp "$RUNNER_TEMP/openqa.XXXXXXXX.json")"
code=0
php "$GITHUB_ACTION_PATH/bin/openqa" html "$OPENQA_FILE" --json > "$report" || code=$?
if [[ "$code" != 0 && "$code" != 1 ]]; then
  rm -f "$report"
  exit 2
fi
count="$(php -r '$r=json_decode(file_get_contents($argv[1]),true,512,JSON_THROW_ON_ERROR); if (!isset($r["issue_count"]) || !is_int($r["issue_count"]) || $r["issue_count"] < 0) exit(2); echo $r["issue_count"];' "$report")"
printf 'issue-count=%s\nreport-path=%s\n' "$count" "$report" >> "$GITHUB_OUTPUT"
printf 'OpenQA findings: %s\n' "$count"
if [[ "$code" == 1 && "$OPENQA_FAIL" == true ]]; then
  exit 1
fi
