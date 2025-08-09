<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\UseCases;

use RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class DownloadZipAction
{
    public static function execute(array $files): BinaryFileResponse
    {
        return (new self())($files);
    }

    /**
     * @throws RuntimeException
     */
    public function __invoke(array $files): BinaryFileResponse
    {
        $zip = new ZipArchive();
        $filename = 'logs.zip';
        $zipPath = storage_path("logs/{$filename}");

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($this->extractPaths($files) as $path) {
                if (file_exists($path)) {
                    $zip->addFile($path, basename($path));
                }
            }

            $zip->close();

            return response()->download($zipPath, $filename)
                ->deleteFileAfterSend();
        }

        throw new RuntimeException('Failed to create zip file.');
    }

    private function extractPaths(array $files): array
    {
        return collect($files)
            ->map(
                static fn (string $log): string => ExtractLogPathAction::execute($log),
            )
            ->all();
    }
}
