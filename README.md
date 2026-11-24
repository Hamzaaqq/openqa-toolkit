# OpenQA Toolkit

A small open-source PHP command-line tool for repeatable website quality checks. Designed for QA learners and developers who want actionable findings without a large framework.

## Quick start

Requires PHP 8.2+ with DOM and cURL extensions. No external PHP packages needed.

    git clone https://github.com/Hamzaaqq/openqa-toolkit.git
    cd openqa-toolkit
    php bin/openqa html examples/sample.html
    php bin/openqa html examples/sample.html --json

The example intentionally contains three findings: missing image alt, a duplicate ID, and a missing local anchor.

For a site you own or have permission to test:

    php bin/openqa url https://example.com --authorized --json

URL mode makes one HTTP GET request, does not follow redirects, times out after 15 seconds and limits the response body to 2 MiB. It reports HTTP status, elapsed request time and advisory security headers. HTML is analyzed only for successful HTML responses.

## Checks

- Missing page title, document language and image alt attributes.
- Duplicate IDs, empty anchor hrefs and unresolved local fragments.
- Missing CSP, Referrer-Policy, X-Content-Type-Options and (HTTPS only) HSTS headers.
- HTTP status and elapsed time in URL mode.

Exit codes: 0 no findings; 1 findings; 2 usage or operational error. JSON mode is suitable for CI. Findings are prompts for manual review, not proof of accessibility compliance or vulnerabilities.

## Development

    php tests/run.php

See [contribution guidelines](CONTRIBUTING.md) and [roadmap](docs/roadmap.md). This is an early version. It does not execute JavaScript, crawl sites, verify external links, or measure browser performance. Never expose the URL checker as an untrusted public web service; it can reach networks available to the local machine.

## License

MIT.
