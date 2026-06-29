<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Widgets\IconsWidget;
use Boquizo\FilamentLogViewer\Widgets\Stat;
use Boquizo\FilamentLogViewer\Widgets\StatsOverviewWidget;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\PanelRegistry;
use Filament\Widgets\StatsOverviewWidget\Stat as FilamentStat;
use Illuminate\Support\ViewErrorBag;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $reg = app(PanelRegistry::class);
    if (! isset($reg->panels['test'])) {
        $reg->register(
            Panel::make()
                ->id('test')
                ->default()
                ->plugin(FilamentLogViewerPlugin::make()),
        );
    }
    Filament::setCurrentPanel(Filament::getPanel('test'));

    app('view')->share('errors', new ViewErrorBag);
});

it('StatsOverviewWidget renders', function () {
    livewire(StatsOverviewWidget::class)->assertSuccessful();
});

it('IconsWidget renders', function () {
    livewire(IconsWidget::class)->assertSuccessful();
});

it('Stat::make() returns FilamentStat for a known level', function () {
    $data = [
        'name' => 'Error',
        'count' => 5,
        'percent' => 50.0,
        'totals' => ['error' => ['color' => '#FF5722']],
    ];
    expect(Stat::make('error', $data))->toBeInstanceOf(FilamentStat::class);
});

it('Stat::make() with all level returns FilamentStat', function () {
    $data = [
        'name' => 'All',
        'count' => 10,
        'percent' => 100.0,
        'totals' => ['all' => ['color' => '#8A8A8A']],
    ];
    expect(Stat::make('all', $data))->toBeInstanceOf(FilamentStat::class);
});
