<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Utils;

use Illuminate\Support\Str;

class Parser
{
    public const string DATE_PATTERN = '\d{4}(-\d{2}){2}';
    public const string TIME_PATTERN = '\d{2}(:\d{2}){2}';
    public const string DATETIME_PATTERN = self::DATE_PATTERN . ' ' . self::TIME_PATTERN;

    protected static array $parsed = [];

    public static function parse(string $raw): array
    {
        static::$parsed = [];

        [$headings, $data] = static::parseRawData($raw);

        if (! is_array($headings)) {
            return static::$parsed;
        }

        foreach ($headings as $heading) {
            for ($i = 0, $j = count($heading); $i < $j; $i++) {
                static::populateEntries($heading, $data, $i);
            }
        }

        unset($headings, $data);

        return array_reverse(static::$parsed);
    }

    public static function extractDate(string $string): string
    {
        $pattern = self::DATE_PATTERN;

        return preg_replace("/.*({$pattern}).*/", '$1', $string);
    }

    private static function parseRawData(string $raw): array
    {
        $datetimePattern = self::DATETIME_PATTERN;

        $pattern = "/\\[{$datetimePattern}].*/";

        preg_match_all($pattern, $raw, $headings);

        $data = preg_split($pattern, $raw);

        if ($data[0] < 1) {
            $trash = array_shift($data);
            unset($trash);
        }

        return [$headings, $data];
    }

    private static function populateEntries(
        array $heading,
        array $data,
        int $key,
    ): void {
        foreach (Level::cases() as $level) {
            if (static::hasLogLevel($heading[$key], $level->value)) {
                static::$parsed[] = [
                    'level' => $level->value,
                    'header' => $heading[$key],
                    'stack' => $data[$key]
                ];
            }
        }
    }

    private static function hasLogLevel(string $heading, string $level): bool
    {
        return Str::contains($heading, Str::upper(".{$level}:"));
    }
}
