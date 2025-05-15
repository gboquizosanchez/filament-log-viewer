<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Utils;

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
        return self::options()['all'] ?? 'All';
    }
}
