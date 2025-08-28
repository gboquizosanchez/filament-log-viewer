<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\UseCases;

use Boquizo\FilamentLogViewer\Entities\Log;
use Illuminate\Support\Arr;
use RuntimeException;

class ExtractLogByDateUseCase
{
    public static function execute(string $date): Log
    {
        return (new self())($date);
    }

    public function __invoke(string $date): Log
    {
        $file = Arr::last(explode('\\', $date));
        $dates = ExtractNamesUseCase::execute();

        if (!isset($dates[$file])) {
            throw new RuntimeException("Log not found in [{$file}]");
        }

        return new Log($date, $dates[$file], ReadLogUseCase::execute($date));
    }
}
