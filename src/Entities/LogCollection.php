<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Entities;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\UseCases\ExtractNamesUseCase;
use Boquizo\FilamentLogViewer\UseCases\ReadLogUseCase;
use Boquizo\FilamentLogViewer\Utils\Level;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Str;

class LogCollection extends LazyCollection
{
    public function __construct(mixed $source = null)
    {
        if ($source !== null) {
            $source = static function () {
                $driver = FilamentLogViewerPlugin::get()->driver();
                $storagePath = Config::string('filament-log-viewer.storage_path', storage_path('logs'));

                foreach (ExtractNamesUseCase::execute() as $date => $path) {
                    $path = Str::replace("{$storagePath}\\", '', $path);
                    $mode = $driver === 'raw' ? $path : $date;

                    yield $mode => Log::make(
                        $date,
                        $path,
                        ReadLogUseCase::execute($mode),
                    );
                }
            };
        }

        parent::__construct($source);
    }

    public function stats(): array
    {
        return array_map(
            static fn (Log $log) => $log->stats(),
            $this->all(),
        );
    }

    public function total(string $level = Level::ALL): int
    {
        return (int) $this->sum(
            fn (Log $log): int => $log->entries($level)->count()
        );
    }
}
