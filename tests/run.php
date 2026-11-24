<?php
declare(strict_types=1);
require __DIR__ . '/../src/Audit.php';
require __DIR__ . '/../src/Http.php';

use OpenQA\Audit;
use OpenQA\Http;

function check(bool $condition, string $label): void
{
    if (!$condition) {
        fwrite(STDERR, "FAIL: $label\n");
        exit(1);
    }
    echo "PASS: $label\n";
}
$clean = '<html lang="en"><head><title>Demo</title></head><body><img src="x.png" alt=""><a href="#main">Go</a><main id="main">Hi</main></body></html>';
check(Audit::html($clean) === [], 'clean document and decorative image');
$bad = '<html><head><title> </title></head><body><img src="x"><p id="a">A</p><p id="a">B</p><a href="#missing">Go</a><a href="">Empty</a></body></html>';
$codes = array_column(Audit::html($bad), 'code');
foreach (['missing_lang', 'missing_title', 'missing_alt', 'duplicate_id', 'missing_fragment', 'empty_href'] as $code) {
    check(in_array($code, $codes, true), $code);
}
check(count($codes) === 6, 'exact finding count');
check(Audit::html('<html lang="en"><title>Demo</title><a name="legacy"></a><a href="#legacy">Link</a><a href="#top">Top</a></html>') === [], 'legacy and top anchors');
check(count(Audit::headers([], 'https')) === 4, 'HTTPS header checks');
check(count(Audit::headers([], 'http')) === 3, 'HTTP header checks');
check(Audit::headers(['Content-Security-Policy'=>'default-src self','X-Content-Type-Options'=>'nosniff','Referrer-Policy'=>'no-referrer','Strict-Transport-Security'=>'max-age=3600'], 'https') === [], 'case-insensitive headers');
try {
    Http::get('file:///etc/passwd');
    check(false, 'reject non-http URLs');
} catch (InvalidArgumentException) {
    check(true, 'reject non-http URLs');
}
echo "All checks passed.\n";
