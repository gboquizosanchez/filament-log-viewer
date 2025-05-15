<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Entities;

use Boquizo\FilamentLogViewer\Utils\Level;
use Boquizo\FilamentLogViewer\Utils\Parser;
use Generator;
use Illuminate\Support\LazyCollection;

class EntryCollection extends LazyCollection
{
    public static function load(string $raw): static
    {
        return new static(function () use ($raw): Generator {
            foreach (Parser::parse($raw) as $entry) {
                [$level, $header, $stack] = array_values($entry);

                yield new Entry($level, $header, $stack);
            }
        });
    }

    public function filterByLevel(string $level): static
    {
        return $this->filter(
            fn (Entry $entry) => $entry->isSame($level),
        );
    }

    public function stats(): array
    {
        $counters = $this->initStats();

        foreach ($this->groupBy('level') as $level => $entries) {
            $countEntries = count($entries);
            $countAll = $countEntries;
            $counters[$level] = $countEntries;
            $counters['all'] += $countAll;
        }

        return $counters;
    }

    private function initStats(): array
    {
        $levels = array_keys(Level::options());

        return array_map(static fn (): int => 0, array_flip($levels));
    }
}
