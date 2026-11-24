<?php
declare(strict_types=1);

namespace OpenQA\Laravel;

use Illuminate\Support\ServiceProvider;

final class OpenQAServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([AuditViewCommand::class]);
        }
    }
}
