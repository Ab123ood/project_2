<?php

class TranslationService
{
    private const GOOGLE_ENDPOINT = 'https://translate.googleapis.com/translate_a/single';
    private const MAX_TEXT_LENGTH = 4500;

    /** @var array<string, string> */
    private static array $cache = [];

    public static function translate(string $text, string $targetLocale, string $sourceLocale = 'auto'): string
    {
        $text = trim($text);
        if ($text === '') {
            return $text;
        }

        $targetLocale = strtolower(trim($targetLocale));
        if ($targetLocale === '') {
            return $text;
        }

        $defaultLocale = strtolower((string)($GLOBALS['config']['app']['locale'] ?? 'ar'));
        if ($targetLocale === $defaultLocale) {
            return $text;
        }

        if (mb_strlen($text, 'UTF-8') > self::MAX_TEXT_LENGTH) {
            return self::translateInChunks($text, $targetLocale, $sourceLocale);
        }

        return self::performTranslation($text, $targetLocale, $sourceLocale);
    }

    /**
     * @param array<int, array<string, mixed>> $records
     * @param array<int, string> $fields
     * @param string $targetLocale
     * @return array<int, array<string, mixed>>
     */
    public static function translateCollection(array $records, array $fields, string $targetLocale): array
    {
        if (empty($records) || empty($fields)) {
            return $records;
        }

        foreach ($records as &$record) {
            if (!is_array($record)) {
                continue;
            }

            foreach ($fields as $field) {
                if (!isset($record[$field]) || !is_string($record[$field])) {
                    continue;
                }

                $record[$field] = self::translate($record[$field], $targetLocale);
            }
        }
        unset($record);

        return $records;
    }

    private static function performTranslation(string $text, string $targetLocale, string $sourceLocale): string
    {
        $cacheKey = self::buildCacheKey($text, $targetLocale, $sourceLocale);
        if (isset(self::$cache[$cacheKey])) {
            return self::$cache[$cacheKey];
        }

        $query = http_build_query([
            'client' => 'gtx',
            'sl' => $sourceLocale,
            'tl' => $targetLocale,
            'dt' => 't',
            'q' => $text,
        ], '', '&', PHP_QUERY_RFC3986);

        $url = self::GOOGLE_ENDPOINT . '?' . $query;

        try {
            $response = self::httpGet($url);
            if ($response === null) {
                return $text;
            }

            $decoded = json_decode($response, true);
            if (!is_array($decoded) || !isset($decoded[0]) || !is_array($decoded[0])) {
                return $text;
            }

            $translated = '';
            foreach ($decoded[0] as $segment) {
                if (!is_array($segment) || !isset($segment[0])) {
                    continue;
                }
                $translated .= (string)$segment[0];
            }

            if ($translated === '') {
                return $text;
            }

            self::$cache[$cacheKey] = $translated;
            return $translated;
        } catch (Throwable $e) {
            error_log('Translation failed: ' . $e->getMessage());
            return $text;
        }
    }

    private static function translateInChunks(string $text, string $targetLocale, string $sourceLocale): string
    {
        $chunks = self::chunkText($text);
        if (count($chunks) === 1) {
            return self::performTranslation($chunks[0], $targetLocale, $sourceLocale);
        }

        $translated = '';
        foreach ($chunks as $chunk) {
            $translated .= self::performTranslation($chunk, $targetLocale, $sourceLocale);
        }

        return $translated;
    }

    /**
     * @return array<int, string>
     */
    private static function chunkText(string $text): array
    {
        $pieces = preg_split('/(\r\n|\n)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        if ($pieces === false || $pieces === []) {
            return [$text];
        }

        $chunks = [];
        $current = '';

        foreach ($pieces as $piece) {
            if ($piece === '') {
                continue;
            }

            if (mb_strlen($piece, 'UTF-8') > self::MAX_TEXT_LENGTH) {
                if ($current !== '') {
                    $chunks[] = $current;
                    $current = '';
                }
                foreach (self::hardSplit($piece) as $split) {
                    $chunks[] = $split;
                }
                continue;
            }

            if ($current !== '' && mb_strlen($current . $piece, 'UTF-8') > self::MAX_TEXT_LENGTH) {
                $chunks[] = $current;
                $current = '';
            }

            $current .= $piece;
        }

        if ($current !== '') {
            $chunks[] = $current;
        }

        return $chunks !== [] ? $chunks : [$text];
    }

    /**
     * @return array<int, string>
     */
    private static function hardSplit(string $text): array
    {
        $length = mb_strlen($text, 'UTF-8');
        if ($length <= self::MAX_TEXT_LENGTH) {
            return [$text];
        }

        $chunks = [];
        for ($offset = 0; $offset < $length; $offset += self::MAX_TEXT_LENGTH) {
            $chunks[] = mb_substr($text, $offset, self::MAX_TEXT_LENGTH, 'UTF-8');
        }

        return $chunks;
    }

    private static function buildCacheKey(string $text, string $targetLocale, string $sourceLocale): string
    {
        return md5($sourceLocale . '|' . $targetLocale . '|' . $text);
    }

    /**
     * @param array<string, mixed> $record
     * @param array<int, string> $fields
     * @param string $targetLocale
     * @return array<string, mixed>
     */
    public static function translateRecord(array $record, array $fields, string $targetLocale): array
    {
        if (empty($record) || empty($fields)) {
            return $record;
        }

        foreach ($fields as $field) {
            if (!isset($record[$field]) || !is_string($record[$field])) {
                continue;
            }

            $record[$field] = self::translate($record[$field], $targetLocale);
        }

        return $record;
    }

    private static function httpGet(string $url): ?string
    {
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            if ($ch === false) {
                return null;
            }

            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTPHEADER => ['User-Agent: TranslationService/1.0'],
            ]);

            $result = curl_exec($ch);
            if ($result === false) {
                curl_close($ch);
                return null;
            }

            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($status >= 200 && $status < 300) {
                return $result;
            }

            return null;
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 10,
                'header' => "User-Agent: TranslationService/1.0\r\n",
            ],
        ]);

        return @file_get_contents($url, false, $context) ?: null;
    }
}
