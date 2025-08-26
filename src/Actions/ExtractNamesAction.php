<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Boquizo\FilamentLogViewer\Utils\Parser;
use Illuminate\Support\Facades\Config;

class ExtractNamesAction
{
    /** @return array<string, string> */
    public static function execute(): array
    {
        return (new self())();
    }

    /** @return array<string, string> */
    public function __invoke(): array
    {
        $files = $this->files();

        // [date => file]
        return array_combine(
            $this->extractDates($files),
            $files,
        );
    }

    /**
     * @param list<string> $files
     *
     * @return array<string, string>
     */
    private function extractDates(array $files): array
    {
        return array_map(
            static fn (string $file): string => Parser::extractDate(basename($file)),
            $files,
        );
    }

    /** @return list<string> */
    private function files(): array
    {
        $storagePath = Config::string('filament-log-viewer.storage_path');

        $files = glob(
            $storagePath.DIRECTORY_SEPARATOR.$this->pattern(),
            defined('GLOB_BRACE') ? GLOB_BRACE : 0
        );

        return array_reverse(
            array_filter(
                array_map('realpath', $files),
            ),
        );
    }

    private function pattern(): string
    {
        $patterns = (object) Config::array('filament-log-viewer.pattern');

        return $patterns->prefix.$patterns->date.$patterns->extension;
    }
}
