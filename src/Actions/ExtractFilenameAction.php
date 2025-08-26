<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Illuminate\Support\Facades\Config;

class ExtractFilenameAction
{
    public static function execute(?string $filename, string $date): string
    {
        $driver = FilamentLogViewerPlugin::get()->driver();

        return match ($driver) {
            'stack', 'raw' => basename($filename ?? ''),
            'daily' => sprintf(
                "%s{$date}.%s",
                Config::string('log-viewer.download.prefix', 'laravel-'),
                Config::string('log-viewer.download.extension', 'log')
            ),
            default => $filename ?? '',
        };
    }
}
