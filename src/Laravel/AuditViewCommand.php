<?php
declare(strict_types=1);

namespace OpenQA\Laravel;

use Illuminate\Console\Command;
use Illuminate\Contracts\View\Factory;
use OpenQA\Audit;
use Symfony\Component\Console\Output\OutputInterface;

final class AuditViewCommand extends Command
{
    protected $signature = 'openqa:view {view : Blade view name} {--json : Emit JSON}';

    protected $description = 'Audit the rendered HTML of a local Blade view without making HTTP requests';

    public function handle(Factory $views): int
    {
        try {
            $html = $views->make((string) $this->argument('view'))->render();
            if (trim($html) === '' || strlen($html) > 2 * 1024 * 1024) {
                throw new \RuntimeException('Expected nonempty rendered HTML of at most 2 MiB.');
            }
            $issues = Audit::html($html);
            $report = ['issue_count' => count($issues), 'issues' => $issues];
            if ($this->option('json')) {
                $this->output->writeln(json_encode($report, JSON_PRETTY_PRINT | JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR), OutputInterface::OUTPUT_RAW);
            } else {
                $this->line('Findings: ' . count($issues));
                foreach ($issues as $issue) {
                    $message = preg_replace('/[\x00-\x1F\x7F]/', '?', $issue['message']);
                    $this->output->writeln('[' . $issue['code'] . '] ' . $message, OutputInterface::OUTPUT_RAW);
                }
            }
            return $issues === [] ? 0 : 1;
        } catch (\Throwable $error) {
            // Avoid printing rendered application data or exception traces.
            $this->error('Unable to audit view. Confirm it exists and renders with no required data.');
            return 2;
        }
    }
}
