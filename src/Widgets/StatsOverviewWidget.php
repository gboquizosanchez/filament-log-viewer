<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Widgets;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Arr;
use Override;

class StatsOverviewWidget extends ChartWidget
{
    /** @var array<string, array<string, array<string, bool>>> */
    protected ?array $options = [
        'scales' => [
            'x' => [
                'display' => false,
            ],
            'y' => [
                'display' => false,
            ],
        ],
    ];

    /** {@inheritdoc} */
    #[Override]
    protected function getData(): array
    {
        $data = $this->data();

        return [
            'labels' => Arr::pluck($data, 'label'),
            'datasets' => [
                [
                    'data' => Arr::pluck($data, 'value'),
                    'backgroundColor' => Arr::pluck($data, 'color'),
                    'hoverBackgroundColor' => Arr::pluck($data, 'highlight'),
                ],
            ],
        ];
    }

    #[Override]
    protected function getType(): string
    {
        return 'doughnut';
    }

    /** @return array<string, array{
     *     label: string,
     *     value: int,
     *     color: string,
     *     highlight: string
     * }>
     */
    private function data(): array
    {
        return FilamentLogViewerPlugin::get()
            ->getViewerStatsTable()
            ->totals()
            ->all();
    }
}
