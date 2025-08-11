<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Tables;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

use const Boquizo\FilamentLogViewer\Utils\LEVEL_ALL;

class StatsTable
{
    public array $rows = [];

    public array $footer = [];

    public static function make(array $data): static
    {
        return new static($data);
    }

    public function __construct(
        private readonly array $data,
    ) {
        $this->rows = $this->prepareRows();
        $this->footer = $this->prepareFooter();
    }

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

    private function prepareFooter(): array
    {
        $footer = [];

        foreach ($this->data as $date => $levels) {
            foreach ($levels as $level => $count) {
                if (!isset($footer[$level])) {
                    $footer[$level] = 0;
                }

                $footer[$level] += $count;
            }
        }

        return $footer;
    }

    public function totals(): Collection
    {
        $totals = Collection::make();

        foreach (Arr::except($this->footer, LEVEL_ALL) as $level => $count) {
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
