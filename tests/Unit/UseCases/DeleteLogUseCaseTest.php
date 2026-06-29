<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\UseCases\DeleteLogUseCase;
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

it('deletes an existing log file and returns true', function () {
    $tmpPath = __DIR__ . '/../../fixtures/logs/laravel-2099-08-02.log';
    file_put_contents($tmpPath, "[2099-08-02 10:00:00] production.INFO: Temp [] []\n");

    expect(DeleteLogUseCase::execute('2099-08-02'))->toBeTrue()
        ->and(file_exists($tmpPath))->toBeFalse();
});

it('static execute delegates to __invoke', function () {
    $tmpPath = __DIR__ . '/../../fixtures/logs/laravel-2099-08-03.log';
    file_put_contents($tmpPath, "[2099-08-03 10:00:00] production.INFO: Temp [] []\n");
    DeleteLogUseCase::execute('2099-08-03');
    expect(file_exists($tmpPath))->toBeFalse();
});

it('throws RuntimeException when file does not exist', function () {
    expect(fn () => DeleteLogUseCase::execute('1999-01-01'))
        ->toThrow(RuntimeException::class);
});
