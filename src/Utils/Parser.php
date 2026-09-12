<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Utils;

use Illuminate\Support\Str;

class Parser
{
    public const DATE_PATTERN = '\d{4}(-\d{2}){2}';

    public const TIME_PATTERN = '\d{2}(:\d{2}){2}';

    public const DATETIME_PATTERN = self::DATE_PATTERN . ' ' . self::TIME_PATTERN;

    /**
     * @var list<array{
     *     level: string,
     *     header: string,
     *     stack: string,
     * }>
     */
    protected static array $parsed = [];

    /**
     * @return list<array{
     *     level: string,
     *     header: string,
     *     stack: string,
     * }>
     */
    public static function parse(string $raw): array
    {
        static::$parsed = [];

        [$headings, $data] = self::parseRawData($raw);

        foreach ($headings as $heading) {
            for ($i = 0, $j = count($heading); $i < $j; $i++) {
                self::populateEntries($heading, $data, $i);
            }
        }

        unset($headings, $data);

        return array_reverse(static::$parsed);
    }

    public static function extractDate(string $string): string
    {
        $pattern = self::DATE_PATTERN;

        return preg_replace("/.*({$pattern}).*/", '$1', $string) ?? $string;
    }

    /**
     * @return array{
     *     array{
     *         list<string>,
     *         list<non-falsy-string>,
     *         list<non-falsy-string>,
     *     },
     *     list<string>,
     * }
     */
    private static function parseRawData(string $raw): array
    {
        $datetimePattern = self::DATETIME_PATTERN;

        $pattern = "/\\[{$datetimePattern}].*/";

        preg_match_all($pattern, $raw, $headings);

        $data = preg_split($pattern, $raw);

        if ($data === false) {
            return [$headings, []];
        }

        if ($data[0] === '') {
            $trash = array_shift($data);
            unset($trash);
        }

        return [$headings, $data];
    }

    /**
     * @param  list<string>  $heading
     * @param  list<string>  $data
     */
    private static function populateEntries(
        array $heading,
        array $data,
        int $key,
    ): void {
        foreach (Level::cases() as $level) {
            if (self::hasLogLevel($heading[$key], $level->value)) {
                static::$parsed[] = [
                    'level' => $level->value,
                    'header' => $heading[$key],
                    'stack' => $data[$key],
                ];
            }
        }
    }

    private static function hasLogLevel(string $heading, string $level): bool
    {
        return Str::contains($heading, Str::upper(".{$level}:"));
    }
}
