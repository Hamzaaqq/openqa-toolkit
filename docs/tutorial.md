# First audit: a five-minute offline exercise

Install PHP 8.2+ with DOM and cURL enabled. Check using `php -v` and `php -m`.

From the checkout run:

```sh
php bin/openqa html examples/sample.html --json
```

Expect three findings: missing image alt attribute, duplicate ID and missing anchor target. Exit status 1 means findings were discovered, not a crash. Copy the example to a scratch file and fix each finding: add appropriate alt text (or an empty alt for a decorative image), make IDs unique, and point the link at an existing ID. Run the audit again; expect zero findings and exit status 0. On PowerShell use `$LASTEXITCODE` to inspect the exit status.

The checks are heuristic and do not replace manual accessibility testing. For real websites use URL mode only with permission and do not expose it as a public web service.

If your output differs, open an issue with your PHP version, OS, command and sanitized fixture.
