<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Utils;

use Illuminate\Support\Str;

enum Level: string
{
    // This is a special case, it's not a level itself.
    // It's used to represent all levels and avoid magic strings.
    public const ALL = 'all';

    case Emergency = 'emergency';
    case Alert = 'alert';
    case Critical = 'critical';
    case Error = 'error';
    case Warning = 'warning';
    case Notice = 'notice';
    case Info = 'info';
    case Debug = 'debug';

    /** @return array<string, string> */
    public static function options(bool $withoutAll = false): array
    {
        $levels = __('filament-log-viewer::log.levels');

        if (! is_array($levels)) {
            return [];
        }

        $labels = array_filter(
            $levels,
            static function (
                $label,
                $level,
            ) {
                return is_string($level) && is_string($label);
            },
            ARRAY_FILTER_USE_BOTH,
        );

        if ($withoutAll) {
            unset($labels[self::ALL]);
        }

        return $labels;
    }

    public function label(): string
    {
        return self::options()[$this->value] ?? $this->value;
    }

    public static function all(): string
    {
        return self::options()[self::ALL] ?? Str::ucfirst(self::ALL);
    }
}
