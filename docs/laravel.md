# Laravel integration

OpenQA offers an optional Artisan command for Laravel 12 and 13. The standalone CLI does not need Laravel. Install the published release into your development environment:

```sh
composer require --dev hamzaaqq/openqa-toolkit:^0.1
php artisan openqa:view welcome --json
```

Laravel package discovery registers the provider automatically. If discovery is disabled, add OpenQA\Laravel\OpenQAServiceProvider::class to bootstrap/providers.php in your development environment.

The command renders a named Blade view with no supplied data, then audits the resulting HTML. It is intended for views that can render in isolation. Use trusted local application code: rendering may execute application logic, and this command is not a sandbox. It does not send an HTTP request itself, execute JavaScript, test routes or simulate a browser. Views requiring controllers, authentication or data should be tested using Laravel's normal feature tests.

Exit codes: 0 no findings; 1 findings; 2 unable to render or audit. JSON output is available with --json.

The Composer PSR-4 autoloader also exposes OpenQA\Audit::html($html) for use with trusted HTML captured from Laravel feature tests.

## Run integration tests

From the toolkit repository run composer update, then:

```sh
vendor/bin/phpunit --bootstrap vendor/autoload.php tests/Laravel
```

The CI matrix targets Laravel 12 with Testbench 10 and Laravel 13 with Testbench 11. See the Integration checks workflow for actual results.
