<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Illuminate\Support\Facades\Config;
use RuntimeException;

class ExtractLogPathAction
{
    public static function execute(string $date): false|string
    {
        return (new self())($date);
    }

    public function __invoke(string $date): false|string
    {
        $path = $this->path($date);

        if ( ! file_exists($path)) {
            throw new RuntimeException(
                "The log(s) could not be located at: {$path}",
            );
        }

        return realpath($path);
    }

    private function path(string $date): string
    {
        $prefix = Config::string('log-viewer.pattern.prefix', 'laravel-');
        $extension = Config::string('log-viewer.pattern.extension', '.log');
        $storagePath = Config::string('log-viewer.storage_path', storage_path('logs'));

        return $storagePath.DIRECTORY_SEPARATOR.$prefix.$date.$extension;
    }
}
