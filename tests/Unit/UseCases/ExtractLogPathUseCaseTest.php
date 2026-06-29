<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\UseCases\ExtractLogPathUseCase;
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

it('resolves to real path for existing fixture (daily driver)', function () {
    $path = ExtractLogPathUseCase::execute('2024-01-15');
    expect($path)->toBeString()->toEndWith('laravel-2024-01-15.log');
});

it('static execute delegates to __invoke', function () {
    expect(ExtractLogPathUseCase::execute('2024-01-16'))->toBeString();
});

it('throws RuntimeException for missing log file on daily driver', function () {
    expect(fn () => ExtractLogPathUseCase::execute('1999-01-01'))
        ->toThrow(RuntimeException::class);
});

it('resolves single driver path — throws when laravel.log does not exist', function () {
    config(['filament-log-viewer.driver' => 'single']);
    // storagePath/laravel.log does not exist in fixtures
    expect(fn () => ExtractLogPathUseCase::execute('whatever'))
        ->toThrow(RuntimeException::class);
});

it('resolves raw driver absolute path', function () {
    config(['filament-log-viewer.driver' => 'raw']);
    $fixturePath = realpath(__DIR__ . '/../../fixtures/logs/laravel-2024-01-15.log');
    expect(ExtractLogPathUseCase::execute($fixturePath))->toBe($fixturePath);
});

it('resolves raw driver relative name — throws when file does not exist', function () {
    config(['filament-log-viewer.driver' => 'raw']);
    expect(fn () => ExtractLogPathUseCase::execute('nonexistent.log'))
        ->toThrow(RuntimeException::class);
});
