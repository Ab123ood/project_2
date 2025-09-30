<?php

class TranslationService
{
    private string $defaultLocale;
    private array $cache = [];

    public function __construct(?string $defaultLocale = null)
    {
        $this->defaultLocale = strtolower($defaultLocale ?: 'ar');
    }

    public function translate(string $text, string $targetLocale): string
    {
        $text = (string)$text;
        $target = strtolower(trim($targetLocale));

        if ($text === '' || $target === '' || $target === $this->defaultLocale) {
            return $text;
        }

        $cacheKey = $target . '|' . md5($text);
        if (array_key_exists($cacheKey, $this->cache)) {
            return $this->cache[$cacheKey];
        }

        $translated = $this->requestTranslation($text, $target);
        if ($translated === null) {
            $translated = $text;
        }

        $this->cache[$cacheKey] = $translated;

        return $translated;
    }

    public function translateCollection(array $records, array $fields, string $targetLocale): array
    {
        if (empty($records) || empty($fields)) {
            return $records;
        }

        foreach ($records as $index => $record) {
            if (!is_array($record)) {
                continue;
            }

            foreach ($fields as $field) {
                if (!is_string($field) || $field === '' || !array_key_exists($field, $record)) {
                    continue;
                }

                $value = $record[$field];
                if (!is_string($value) || trim($value) === '') {
                    continue;
                }

                $records[$index][$field] = $this->translate($value, $targetLocale);
            }
        }

        return $records;
    }

    public function translateRecord(array $record, array $fields, string $targetLocale): array
    {
        $translated = $this->translateCollection([$record], $fields, $targetLocale);
        return $translated[0] ?? $record;
    }

    private function requestTranslation(string $text, string $targetLocale): ?string
    {
        $endpoint = 'https://translate.googleapis.com/translate_a/single';
        $params = http_build_query([
            'client' => 'gtx',
            'sl' => 'auto',
            'tl' => $targetLocale,
            'dt' => 't',
            'q' => $text,
        ], '', '&', PHP_QUERY_RFC3986);

        $url = $endpoint . '?' . $params;
        $response = $this->httpGet($url);
        if ($response === null) {
            return null;
        }

        $data = json_decode($response, true);
        if (!is_array($data) || !isset($data[0]) || !is_array($data[0])) {
            return null;
        }

        $translated = '';
        foreach ($data[0] as $segment) {
            if (!is_array($segment) || !isset($segment[0]) || !is_string($segment[0])) {
                continue;
            }
            $translated .= $segment[0];
        }

        return $translated !== '' ? $translated : null;
    }

    private function httpGet(string $url): ?string
    {
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            if ($ch === false) {
                return null;
            }

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');

            $body = curl_exec($ch);
            if ($body === false) {
                curl_close($ch);
                return null;
            }

            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return $status === 200 ? $body : null;
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 5,
                'header' => "User-Agent: Mozilla/5.0\r\n",
            ],
        ]);

        $body = @file_get_contents($url, false, $context);
        return $body !== false ? $body : null;
    }
}
