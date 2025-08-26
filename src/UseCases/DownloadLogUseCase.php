<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\UseCases;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadLogUseCase
{
    public static function execute(string $file): BinaryFileResponse
    {
        return (new self())($file);
    }

    public function __invoke(
        string $file,
        ?string $filename = null,
        array $headers = [],
    ): BinaryFileResponse {
        $filename = ExtractFilenameUseCase::execute($filename, $file);
        $path = ExtractLogPathUseCase::execute($file);

        return response()->download($path, $filename, $headers);
    }
}
