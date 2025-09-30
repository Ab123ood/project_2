<?php

class TranslationService
{
    private const BASE_URL = 'https://translate.googleapis.com/translate_a/single';
    private const SUPPORTED_TARGETS = ['en', 'ar'];

    private static array $cache = [];

    public static function translateText(string $text, string $targetLocale): string
    {
        $target = self::normalizeLocale($targetLocale);
        $trimmed = trim($text);

        if ($trimmed === '' || $target === '') {
            return $text;
        }

        $cacheKey = md5($target . '|' . $trimmed);
        if (isset(self::$cache[$cacheKey])) {
            return self::$cache[$cacheKey];
        }

        $query = http_build_query([
            'client' => 'gtx',
            'sl' => 'auto',
            'tl' => $target,
            'dt' => 't',
            'q' => $trimmed,
        ], '', '&', PHP_QUERY_RFC3986);

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => [
                    'User-Agent: Mozilla/5.0',
                    'Accept: application/json',
                ],
                'timeout' => 5,
            ],
        ]);

        $translated = $text;

        try {
            $response = file_get_contents(self::BASE_URL . '?' . $query, false, $context);
            if ($response === false) {
                return $text;
            }

            $payload = json_decode($response, true);
            if (!is_array($payload) || !isset($payload[0]) || !is_array($payload[0])) {
                return $text;
            }

            $segments = array_filter($payload[0], static function ($chunk) {
                return is_array($chunk) && isset($chunk[0]);
            });

            if (empty($segments)) {
                return $text;
            }

            $translated = implode('', array_map(static function ($chunk) {
                return (string)$chunk[0];
            }, $segments));
        } catch (Throwable $e) {
            // Preserve original text on failure
            $translated = $text;
        }

        self::$cache[$cacheKey] = $translated;
        return $translated;
    }

    public static function translateCollection(array $items, array $fields, string $targetLocale): array
    {
        if (empty($items)) {
            return $items;
        }

        foreach ($items as &$item) {
            if (!is_array($item)) {
                continue;
            }

            foreach ($fields as $field) {
                if (!isset($item[$field]) || !is_string($item[$field])) {
                    continue;
                }

                $item[$field] = self::translateText($item[$field], $targetLocale);
            }
        }

        return $items;
    }

    private static function normalizeLocale(string $locale): string
    {
        $locale = strtolower(substr(trim($locale), 0, 2));
        return in_array($locale, self::SUPPORTED_TARGETS, true) ? $locale : '';
    }
}
