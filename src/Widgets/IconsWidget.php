<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Widgets;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Utils\Level;
use Filament\Widgets\StatsOverviewWidget;
use Illuminate\Support\Arr;

use const Boquizo\FilamentLogViewer\Utils\LEVEL_ALL;

class IconsWidget extends StatsOverviewWidget
{
    public function getStats(): array
    {
        $stats = [];

        foreach ($this->percentages() as $level => $data) {
            $stats[] = Stat::make($level, $data);
        }

        return $stats;
    }

    /** @return array{name: string, count: int, percent: float}[] */
    protected function percentages(): array
    {
        $statsTable = FilamentLogViewerPlugin::get()
            ->getViewerStatsTable();

        $levels = $statsTable->footer;
        $names = $this->names();
        $percents = [];
        $all = Arr::get($levels, LEVEL_ALL);

        foreach ($levels as $level => $count) {
            $percents[$level] = [
                'name' => $names[$level],
                'count' => $count,
                'percent' => $all ? round(($count / $all) * 100, 2) : 0,
                'totals' => $statsTable->totals()->all(),
            ];
        }

        return $percents;
    }

    private function names(): array
    {
        return array_merge_recursive([
            'date' => __('filament-log-viewer::log.table.columns.date.label'),
        ], Level::options());
    }
}
