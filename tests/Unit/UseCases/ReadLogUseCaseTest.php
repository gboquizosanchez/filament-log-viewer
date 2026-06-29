<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\UseCases\ReadLogUseCase;
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

it('reads log content for existing date', function () {
    expect(ReadLogUseCase::execute('2024-01-15'))
        ->toBeString()
        ->toContain('production.ERROR');
});

it('static execute delegates to __invoke', function () {
    expect(ReadLogUseCase::execute('2024-01-16'))->toBeString();
});

it('throws RuntimeException for missing log', function () {
    expect(fn () => ReadLogUseCase::execute('1999-01-01'))
        ->toThrow(RuntimeException::class);
});
