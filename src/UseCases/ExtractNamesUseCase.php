<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\UseCases;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Utils\Parser;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class ExtractNamesUseCase
{
    /** @return array<string, string> */
    public static function execute(): array
    {
        return (new self)();
    }

    /** @return array<string, string> */
    public function __invoke(): array
    {
        $files = $this->files();

        // [date => file]
        return array_combine(
            $this->extractNames($files),
            $files,
        );
    }

    /**
     * @param  list<string>  $files
     * @return list<string>
     */
    private function extractNames(array $files): array
    {
        $driver = FilamentLogViewerPlugin::get()->driver();

        $extractor = match ($driver) {
            'daily' => static fn (string $file): string => Parser::extractDate(basename($file)),
            'single', 'raw' => static fn (string $file): string => basename($file),
        };

        return array_map($extractor, $files);
    }

    /** @return list<string> */
    private function files(): array
    {
        return array_values(array_reverse(
            array_filter(
                array_map(
                    realpath(...),
                    $this->globFiles(),
                ),
                is_string(...),
            ),
        ));
    }

    /** @return list<string> */
    private function globFiles(): array
    {
        $storagePath = Config::string('filament-log-viewer.storage_path');
        $driver = FilamentLogViewerPlugin::get()->driver();

        if ($driver === 'raw') {
            return array_values(array_map(
                static fn (\SplFileInfo $file): string => $file->getPathname(),
                File::allFiles($storagePath),
            ));
        }

        return glob(
            $storagePath . DIRECTORY_SEPARATOR . $this->pattern(),
            defined('GLOB_BRACE') ? GLOB_BRACE : 0
        ) ?: [];
    }

    private function pattern(): string
    {
        $prefix = Config::string('filament-log-viewer.pattern.prefix');
        $date = Config::string('filament-log-viewer.pattern.date');
        $extension = Config::string('filament-log-viewer.pattern.extension');

        return match (FilamentLogViewerPlugin::get()->driver()) {
            'daily' => $prefix . $date . $extension,
            'single' => rtrim($prefix, '-') . $extension,
            'raw' => "*{$extension}",
        };
    }
}
