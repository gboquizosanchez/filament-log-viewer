<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Entities;

use Boquizo\FilamentLogViewer\Utils\Level;
use Boquizo\FilamentLogViewer\Utils\Parser;
use Generator;
use Illuminate\Support\LazyCollection;

/**
 * @phpstan-consistent-constructor
 *
 * @extends LazyCollection<int, Entry>
 */
class EntryCollection extends LazyCollection
{
    public static function load(string $raw): static
    {
        return new static(static function () use ($raw): Generator {
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

    /** @return LevelCounters */
    public function stats(): array
    {
        $counters = $this->initStats();

        foreach (collect($this->all())->groupBy('level') as $level => $entries) {
            $countEntries = $entries->count();
            $countAll = $countEntries;
            $counters[$level] = $countEntries;
            $counters[Level::ALL] += $countAll;
        }

        return $counters;
    }

    /** @return LevelCounters */
    private function initStats(): array
    {
        return [
            Level::ALL => 0,
            Level::Emergency->value => 0,
            Level::Alert->value => 0,
            Level::Critical->value => 0,
            Level::Error->value => 0,
            Level::Warning->value => 0,
            Level::Notice->value => 0,
            Level::Info->value => 0,
            Level::Debug->value => 0,
        ];
    }
}
