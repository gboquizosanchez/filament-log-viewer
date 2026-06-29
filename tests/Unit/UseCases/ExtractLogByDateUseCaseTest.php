<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\Entities\Log;
use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\UseCases\ExtractLogByDateUseCase;
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

it('returns Log for existing date', function () {
    expect(ExtractLogByDateUseCase::execute('2024-01-15'))->toBeInstanceOf(Log::class);
});

it('static execute delegates to __invoke', function () {
    expect(ExtractLogByDateUseCase::execute('2024-01-16'))->toBeInstanceOf(Log::class);
});

it('throws RuntimeException for unknown date', function () {
    expect(fn () => ExtractLogByDateUseCase::execute('1999-01-01'))
        ->toThrow(RuntimeException::class);
});
