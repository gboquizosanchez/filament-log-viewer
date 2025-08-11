<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Utils;

use Illuminate\Support\Str;

// This is a special case, it's not a level itself.
// It's used to represent all levels and avoid magic strings.
const LEVEL_ALL = 'all';

enum Level: string
{
    case Emergency = 'emergency';
    case Alert = 'alert';
    case Critical = 'critical';
    case Error = 'error';
    case Warning = 'warning';
    case Notice = 'notice';
    case Info = 'info';
    case Debug = 'debug';

    public static function options(): array
    {
        return __('filament-log-viewer::log.levels');
    }

    public function label(): string
    {
        return self::options()[$this->value] ?? $this->value;
    }

    public static function all(): string
    {
        return self::options()[LEVEL_ALL] ?? Str::ucfirst('All');
    }
}
