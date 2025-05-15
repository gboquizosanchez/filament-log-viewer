<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Entities;

use Boquizo\FilamentLogViewer\Actions\ExtractDatesAction;
use Boquizo\FilamentLogViewer\Actions\ReadLogAction;
use Illuminate\Support\LazyCollection;

class LogCollection extends LazyCollection
{
    public function __construct(mixed $source = null)
    {
        if ($source !== null) {
            $source = static function () {
                foreach (ExtractDatesAction::execute() as $date => $path) {
                    yield $date => Log::make(
                        $date,
                        $path,
                        ReadLogAction::execute($date),
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

    public function total(string $level = 'all'): int
    {
        return (int) $this->sum(
            fn (Log $log): int => $log->entries($level)->count()
        );
    }
}
