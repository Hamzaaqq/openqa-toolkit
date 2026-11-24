<?php
declare(strict_types=1);

namespace OpenQA;

final class Http
{
    public static function get(string $url): array
    {
        $parts = parse_url($url);
        if (!$parts || !in_array(strtolower($parts['scheme'] ?? ''), ['http', 'https'], true)
            || empty($parts['host']) || isset($parts['user']) || isset($parts['pass'])) {
            throw new \InvalidArgumentException('Use an HTTP(S) URL without embedded credentials.');
        }
        $body = '';
        $headers = [];
        $limit = 2 * 1024 * 1024;
        $handle = curl_init($url);
        if ($handle === false) {
            throw new \RuntimeException('Could not initialize HTTP client.');
        }
        curl_setopt_array($handle, [
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_USERAGENT => 'OpenQA-Toolkit/0.1',
            CURLOPT_HEADERFUNCTION => static function ($ch, string $line) use (&$headers): int {
                if (str_starts_with($line, 'HTTP/')) {
                    $headers = [];
                } elseif (str_contains($line, ':')) {
                    [$name, $value] = explode(':', $line, 2);
                    $headers[strtolower(trim($name))] = trim($value);
                }
                return strlen($line);
            },
            CURLOPT_WRITEFUNCTION => static function ($ch, string $chunk) use (&$body, $limit): int {
                if (strlen($body) + strlen($chunk) > $limit) {
                    return 0;
                }
                $body .= $chunk;
                return strlen($chunk);
            },
        ]);
        try {
            if (curl_exec($handle) === false) {
                throw new \RuntimeException('HTTP request failed or exceeded 2 MiB: ' . curl_error($handle));
            }
            return [
                'status' => (int)curl_getinfo($handle, CURLINFO_RESPONSE_CODE),
                'elapsed_ms' => (int)round(curl_getinfo($handle, CURLINFO_TOTAL_TIME) * 1000),
                'headers' => $headers,
                'body' => $body,
                'scheme' => strtolower($parts['scheme']),
            ];
        } finally {
            curl_close($handle);
        }
    }
}
