<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Illuminate\Support\Facades\Config;

class ExtractDatesAction
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
            fn (string $file): string => $this->extractDate(basename($file)),
            $files,
        );
    }

    private function extractDate(string $date): string
    {
        return preg_replace("/.*(\\d{4}(-\\d{2}){2}).*/", '$1', $date);
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
