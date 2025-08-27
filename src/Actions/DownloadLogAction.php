<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadLogAction
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
        $filename = ExtractFilenameAction::execute($filename, $file);
        $path = ExtractLogPathAction::execute($file);

        return response()->download($path, $filename, $headers);
    }
}
