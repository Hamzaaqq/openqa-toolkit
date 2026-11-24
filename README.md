# OpenQA Toolkit

[![PHP checks](https://github.com/Hamzaaqq/openqa-toolkit/actions/workflows/ci.yml/badge.svg)](https://github.com/Hamzaaqq/openqa-toolkit/actions/workflows/ci.yml) [![Integration checks](https://github.com/Hamzaaqq/openqa-toolkit/actions/workflows/integrations.yml/badge.svg)](https://github.com/Hamzaaqq/openqa-toolkit/actions/workflows/integrations.yml) [![Website deployment](https://github.com/Hamzaaqq/openqa-toolkit/actions/workflows/pages.yml/badge.svg)](https://github.com/Hamzaaqq/openqa-toolkit/actions/workflows/pages.yml) [![Packagist](https://img.shields.io/packagist/v/hamzaaqq/openqa-toolkit)](https://packagist.org/packages/hamzaaqq/openqa-toolkit) [![MIT license](https://img.shields.io/badge/license-MIT-green)](LICENSE)

OpenQA Toolkit is a lightweight, open-source PHP CLI for website quality assurance: HTML quality checks, accessibility-related heuristics, local anchor validation and advisory HTTP security-header review. Audit local HTML, rendered Laravel Blade views or generated pages in GitHub Actions.

**[Official documentation](https://hamzaaqq.github.io/openqa-toolkit/) · [Quick start](https://hamzaaqq.github.io/openqa-toolkit/guide.html) · [Integrations](https://hamzaaqq.github.io/openqa-toolkit/integrations.html) · [Packagist](https://packagist.org/packages/hamzaaqq/openqa-toolkit) · [Contribute](https://hamzaaqq.github.io/openqa-toolkit/contribute.html)**

```sh
composer require --dev hamzaaqq/openqa-toolkit:^0.1
```

Documentation website source lives in [site/](site/). See the [Pages deployment workflow](.github/workflows/pages.yml) for publication status.

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

## Install as a Composer CLI

The package is registered on [Packagist](https://packagist.org/packages/hamzaaqq/openqa-toolkit). Install the published release in a separate project with `composer require --dev hamzaaqq/openqa-toolkit:^0.1`. Alternatively, install from Git:

```sh
composer config repositories.openqa vcs https://github.com/Hamzaaqq/openqa-toolkit
composer require --dev hamzaaqq/openqa-toolkit:dev-main
```

Run `vendor/bin/openqa --version` (Windows Command Prompt: `vendor\bin\openqa.bat --version`). See the [v0.1.0 release](https://github.com/Hamzaaqq/openqa-toolkit/releases/tag/v0.1.0).

See [the first audit tutorial](docs/tutorial.md) for a practical offline exercise and [release checklist](docs/releasing.md) for publication steps.

## Integrations

- [Laravel Artisan integration](docs/laravel.md): audit rendered Blade views locally with `php artisan openqa:view welcome --json`.
- [Reusable GitHub Action](docs/github-action.md): audit generated HTML files in CI and export JSON findings.

Both integrations are optional. See the [integration workflow](.github/workflows/integrations.yml) for test coverage and current CI results.

## Help improve OpenQA

Try the [five-minute offline exercise](docs/tutorial.md) and tell us what worked or failed on your platform. We welcome reproducible feedback and small contributions, especially [Windows installation checks](https://github.com/Hamzaaqq/openqa-toolkit/issues/1), [anchor regression tests](https://github.com/Hamzaaqq/openqa-toolkit/issues/2), and [local HTTP integration tests](https://github.com/Hamzaaqq/openqa-toolkit/issues/3). Check the issue discussion before starting work.

## Development

    php tests/run.php

See [contribution guidelines](CONTRIBUTING.md) and [roadmap](docs/roadmap.md). This is an early version. It does not execute JavaScript, crawl sites, verify external links, or measure browser performance. Never expose the URL checker as an untrusted public web service; it can reach networks available to the local machine.

## License

MIT.
