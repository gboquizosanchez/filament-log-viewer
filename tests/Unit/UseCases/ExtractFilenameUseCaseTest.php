<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\UseCases\ExtractFilenameUseCase;
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

it('returns basename for single driver', function () {
    config(['filament-log-viewer.driver' => 'single']);
    expect(ExtractFilenameUseCase::execute('/path/to/laravel.log', '2024-01-15'))
        ->toBe('laravel.log');
});

it('returns basename for raw driver', function () {
    config(['filament-log-viewer.driver' => 'raw']);
    expect(ExtractFilenameUseCase::execute('/path/to/myapp.log', '2024-01-15'))
        ->toBe('myapp.log');
});

it('returns empty string basename for null filename on single driver', function () {
    config(['filament-log-viewer.driver' => 'single']);
    expect(ExtractFilenameUseCase::execute(null, '2024-01-15'))->toBe('');
});

it('returns formatted filename containing date for daily driver', function () {
    // Uses log-viewer.download.prefix (defaults to 'laravel-') and extension ('log')
    $result = ExtractFilenameUseCase::execute(null, '2024-01-15');
    expect($result)->toContain('2024-01-15');
});
