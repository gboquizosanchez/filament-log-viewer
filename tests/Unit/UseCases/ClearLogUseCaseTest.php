<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\UseCases\ClearLogUseCase;
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

it('clears an existing log file and returns true', function () {
    $tmpPath = __DIR__ . '/../../fixtures/logs/laravel-2099-01-01.log';
    file_put_contents($tmpPath, "[2099-01-01 10:00:00] production.INFO: Temp [] []\n");

    expect(ClearLogUseCase::execute('2099-01-01'))->toBeTrue()
        ->and(file_get_contents($tmpPath))->toBe('');

    unlink($tmpPath);
});

it('returns false when log does not exist', function () {
    expect(ClearLogUseCase::execute('1999-01-01'))->toBeFalse();
});
