<?php
declare(strict_types=1);

namespace OpenQA;

final class Audit
{
    public static function html(string $html): array
    {
        $doc = new \DOMDocument();
        if (!$doc->loadHTML($html, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING)) {
            throw new \RuntimeException('Unable to parse HTML.');
        }
        $issues = [];
        $add = static function (string $code, string $message) use (&$issues): void {
            $issues[] = ['code' => $code, 'message' => $message];
        };
        $root = $doc->documentElement;
        if (!$root || trim($root->getAttribute('lang')) === '') {
            $add('missing_lang', 'Document has no language declaration.');
        }
        $titles = $doc->getElementsByTagName('title');
        if (!$titles->length || trim($titles->item(0)->textContent) === '') {
            $add('missing_title', 'Document has no nonempty title.');
        }
        foreach ($doc->getElementsByTagName('img') as $img) {
            if (!$img->hasAttribute('alt')) {
                $add('missing_alt', 'Image has no alt attribute.');
            }
        }
        $ids = [];
        foreach ($doc->getElementsByTagName('*') as $node) {
            if ($node->hasAttribute('id')) {
                $id = $node->getAttribute('id');
                if ($id !== '' && isset($ids[$id])) {
                    $add('duplicate_id', 'Duplicate id: ' . $id);
                }
                $ids[$id] = true;
            }
        }
        foreach ($doc->getElementsByTagName('a') as $link) {
            if (!$link->hasAttribute('href')) {
                continue;
            }
            $href = trim($link->getAttribute('href'));
            if ($href === '') {
                $add('empty_href', 'Anchor has an empty href.');
            } elseif (str_starts_with($href, '#') && strlen($href) > 1) {
                $target = rawurldecode(substr($href, 1));
                if (!isset($ids[$target]) && !str_contains($target, ':~:')) {
                    $found = false;
                    foreach ($doc->getElementsByTagName('a') as $anchor) {
                        if ($anchor->getAttribute('name') === $target) {
                            $found = true;
                            break;
                        }
                    }
                    if (!$found && strtolower($target) !== 'top') {
                        $add('missing_fragment', 'Missing local anchor: ' . $target);
                    }
                }
            }
        }
        return $issues;
    }

    public static function headers(array $headers, string $scheme): array
    {
        $headers = array_change_key_case($headers, CASE_LOWER);
        $checks = [
            'content-security-policy' => 'Consider a Content Security Policy appropriate to this site.',
            'x-content-type-options' => 'Consider X-Content-Type-Options: nosniff.',
            'referrer-policy' => 'Consider an explicit Referrer-Policy.',
        ];
        if ($scheme === 'https') {
            $checks['strict-transport-security'] = 'Consider HSTS after confirming HTTPS readiness.';
        }
        $issues = [];
        foreach ($checks as $name => $message) {
            if (trim((string)($headers[$name] ?? '')) === '') {
                $issues[] = ['code' => 'missing_header', 'message' => $message, 'header' => $name];
            }
        }
        return $issues;
    }
}
