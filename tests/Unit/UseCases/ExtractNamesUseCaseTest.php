<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\UseCases\ExtractNamesUseCase;
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

it('returns array keyed by date for daily driver', function () {
    $names = ExtractNamesUseCase::execute();
    expect($names)->toBeArray()
        ->toHaveKey('2024-01-16')
        ->toHaveKey('2024-01-15');
});

it('most recent date appears first', function () {
    expect(array_key_first(ExtractNamesUseCase::execute()))->toBe('2024-01-16');
});

it('static execute delegates to __invoke', function () {
    expect(ExtractNamesUseCase::execute())->toBeArray();
});

it('returns array for raw driver using File::allFiles', function () {
    config(['filament-log-viewer.driver' => 'raw']);
    $names = ExtractNamesUseCase::execute();
    expect($names)->toBeArray();
});

it('returns array for single driver', function () {
    config(['filament-log-viewer.driver' => 'single']);
    $names = ExtractNamesUseCase::execute();
    expect($names)->toBeArray();
});
