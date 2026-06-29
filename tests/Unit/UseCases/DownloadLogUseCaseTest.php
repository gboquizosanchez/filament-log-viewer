<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\UseCases\DownloadLogUseCase;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\PanelRegistry;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

it('execute() returns BinaryFileResponse for existing log', function () {
    $response = DownloadLogUseCase::execute('2024-01-15');
    expect($response)->toBeInstanceOf(BinaryFileResponse::class);
});

it('static execute delegates to __invoke', function () {
    expect(DownloadLogUseCase::execute('2024-01-16'))->toBeInstanceOf(BinaryFileResponse::class);
});
