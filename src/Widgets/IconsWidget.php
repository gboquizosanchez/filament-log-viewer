<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Widgets;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Overrides\Stat;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Override;

class IconsWidget extends BaseWidget
{
    /** {@inheritdoc} */
    #[Override]
    public function getStats(): array
    {
        $percentages = $this->percentages();

        $stats = [];

        foreach ($percentages as $level => $data) {
            $color = Arr::get($data, "totals.{$level}.color", '#8A8A8A');

            $stats[] = Stat::make(
                label: $data['name'],
                value: $data['count'],
            )
                ->icon(Config::string("filament-log-viewer.icons.{$level}"))
                ->progress($data['percent'])
                ->iconBackgroundColor($color)
                ->progressBarColor($color)
                ->iconPosition('start')
                ->description("{$data['percent']}%");
        }

        return $stats;
    }

    /** @return array{name: string, count: int, percent: float}[] */
    protected function percentages(): array
    {
        $statsTable = FilamentLogViewerPlugin::get()
            ->getViewerStatsTable();

        $levels = $statsTable->footer();
        $names = $statsTable->header();
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
}
