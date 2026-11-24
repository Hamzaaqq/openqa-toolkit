<?php
declare(strict_types=1);

namespace OpenQA\Tests;

use Illuminate\Support\Facades\Artisan;
use OpenQA\Laravel\OpenQAServiceProvider;
use Orchestra\Testbench\TestCase;

final class AuditViewTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [OpenQAServiceProvider::class];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app['view']->addLocation(__DIR__ . '/views');
    }

    public function test_clean_view_passes(): void
    {
        $this->artisan('openqa:view', ['view' => 'openqa-clean'])
            ->expectsOutput('Findings: 0')
            ->assertExitCode(0);
    }

    public function test_rendered_blade_findings_are_json(): void
    {
        $code = Artisan::call('openqa:view', ['view' => 'openqa-bad', '--json' => true]);
        $this->assertSame(1, $code);
        $report = json_decode(Artisan::output(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame(1, $report['issue_count']);
        $this->assertSame(['missing_alt'], array_column($report['issues'], 'code'));
    }

    public function test_unknown_view_returns_operational_error(): void
    {
        $this->artisan('openqa:view', ['view' => 'missing-openqa-view'])->assertExitCode(2);
    }
}
