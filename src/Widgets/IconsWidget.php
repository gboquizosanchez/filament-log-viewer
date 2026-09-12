<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Widgets;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Utils\Level;
use Filament\Widgets\StatsOverviewWidget;

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

    /**
     * @return array<string, array{
     *     name: string,
     *     count: int,
     *     percent: float|int,
     *     totals: array<string, array{
     *         label: string,
     *         value: int,
     *         color: string,
     *         highlight: string,
     *     }>,
     * }>
     */
    protected function percentages(): array
    {
        $statsTable = FilamentLogViewerPlugin::get()
            ->getViewerStats();

        $levels = $statsTable->footer;
        $names = $this->names();
        $percents = [];
        $all = $levels[Level::ALL] ?? 0;

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

    /** @return array<string, string> */
    private function names(): array
    {
        return [
            'date' => __('filament-log-viewer::log.table.columns.date.label'),
        ] + Level::options();
    }
}
