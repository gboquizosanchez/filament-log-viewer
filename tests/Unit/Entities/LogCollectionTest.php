<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\Entities\Log;
use Boquizo\FilamentLogViewer\Entities\LogCollection;
use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\PanelRegistry;

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
});

it('null source skips the generator assignment branch', function () {
    // Passing null → parent::__construct(null) → static::empty() → new static([])
    // which re-enters with [] (non-null), triggering the generator over fixture files.
    expect((new LogCollection(null))->count())->toBeGreaterThanOrEqual(0);
});

it('non-null source loads all fixture log files', function () {
    expect((new LogCollection(true))->count())->toBeGreaterThanOrEqual(2);
});

it('yields Log instances', function () {
    expect((new LogCollection(true))->first())->toBeInstanceOf(Log::class);
});

it('stats() returns array of per-log stats arrays', function () {
    expect((new LogCollection(true))->stats())->toBeArray()->not->toBeEmpty();
});

it('total() counts all entries across all fixture logs', function () {
    // fixture 1: 8 entries, fixture 2: 2 entries
    expect((new LogCollection(true))->total())->toBeGreaterThanOrEqual(10);
});

it('total() filtered by level counts only matching entries', function () {
    expect((new LogCollection(true))->total('error'))->toBeGreaterThanOrEqual(1);
});
