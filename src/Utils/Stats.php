<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Utils;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

/** @phpstan-consistent-constructor */
class Stats
{
    /** @var array<string, LogRow> */
    public array $rows = [];

    /** @var array<string, int> */
    public array $footer = [];

    /**
     * @param  array<string, LevelCounters>  $data
     */
    public static function make(array $data): static
    {
        return new static($data);
    }

    public function __construct(
        /** @var array<string, LevelCounters> */
        private readonly array $data,
    ) {
        $this->rows = $this->prepareRows();
        $this->footer = $this->prepareFooter();
    }

    /** @return array<string, LogRow> */
    private function prepareRows(): array
    {
        $rows = [];

        foreach ($this->data as $date => $levels) {
            $rows[$date] = array_merge(
                ['date' => $date],
                $levels,
            );
        }

        return $rows;
    }

    /** @return array<string, int> */
    private function prepareFooter(): array
    {
        $footer = [];

        foreach ($this->data as $levels) {
            foreach ($levels as $level => $count) {
                if (! isset($footer[$level])) {
                    $footer[$level] = 0;
                }

                $footer[$level] += $count;
            }
        }

        return $footer;
    }

    /**
     * @return Collection<string, array{
     *     label: string,
     *     value: int,
     *     color: string,
     *     highlight: string,
     * }>
     */
    public function totals(): Collection
    {
        $totals = Collection::make();

        foreach (Arr::except($this->footer, Level::ALL) as $level => $count) {
            $totals->put($level, [
                'label' => __("filament-log-viewer::log.levels.{$level}"),
                'value' => $count,
                'color' => Config::string("filament-log-viewer.colors.levels.{$level}"),
                'highlight' => Config::string("filament-log-viewer.colors.levels.{$level}"),
            ]);
        }

        return $totals;
    }
}
