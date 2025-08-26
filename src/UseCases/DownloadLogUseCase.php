<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\UseCases;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadLogUseCase
{
    public static function execute(string $date): BinaryFileResponse
    {
        return (new self())($date);
    }

    public function __invoke(
        string $date,
        ?string $filename = null,
        array $headers = [],
    ): BinaryFileResponse {
        $driver = FilamentLogViewerPlugin::get()->driver();

        $filename = match ($driver) {
            'stack' => basename($filename ?? ''),
            'daily' => sprintf(
                "%s{$date}.%s",
                Config::string('log-viewer.download.prefix', 'laravel-'),
                Config::string('log-viewer.download.extension', 'log')
            ),
            default => $filename,
        };

        $path = ExtractLogPathUseCase::execute($date);

        return response()->download($path, $filename, $headers);
    }
}
