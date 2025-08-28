<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\UseCases;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Illuminate\Support\Facades\Config;
use RuntimeException;

class ExtractLogPathUseCase
{
    public static function execute(string $name): false|string
    {
        return (new self())($name);
    }

    public function __invoke(string $name): false|string
    {
        $path = $this->path($name);

        if ( ! file_exists($path)) {
            throw new RuntimeException(
                "The log(s) could not be located at: {$path}",
            );
        }

        return realpath($path);
    }

    private function path(string $name): string
    {
        $prefix = Config::string('filament-log-viewer.pattern.prefix', 'laravel-');
        $extension = Config::string('filament-log-viewer.pattern.extension', '.log');
        $storagePath = Config::string('filament-log-viewer.storage_path', storage_path('logs'));

        $basePath = $storagePath.DIRECTORY_SEPARATOR;

        return match (FilamentLogViewerPlugin::get()->driver()) {
            'daily' => $basePath.$prefix.$name.$extension,
            'stack' => $basePath.rtrim($prefix, '-').$extension,
            'raw' => $basePath.$name,
        };
    }
}
