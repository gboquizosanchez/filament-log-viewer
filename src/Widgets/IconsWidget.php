<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Widgets;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Utils\Level;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\HtmlString;

class IconsWidget extends StatsOverviewWidget
{
    protected ?string $maxHeight = '300px';

    public function getStats(): array
    {
        $stats = [];

        foreach ($this->percentages() as $level => $data) {
            $progressColor = Arr::get($data, "totals.{$level}.color", '#8A8A8A');

            $stats[] = Stat::make(
                label: $data['name'],
                value: $data['count'],
            )
                ->label(new HtmlString(
                    "<div style='color: {$progressColor}'>{$data['name']}</div>"
                ))
                ->icon(Config::string("filament-log-viewer.icons.{$level}"))
                ->description(
                    new HtmlString(
                        view('filament-log-viewer::progress-bar', [
                            'progressColor' => $progressColor,
                            'percent' => $data['percent'],
                        ]),
                    ),
                );
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
        $all = Arr::get($levels, 'all');

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
