<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\UseCases\DownloadZipUseCase;
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

it('execute() creates zip and returns BinaryFileResponse', function () {
    $response = DownloadZipUseCase::execute(['2024-01-15', '2024-01-16']);
    expect($response)->toBeInstanceOf(BinaryFileResponse::class);
});

it('static execute delegates to __invoke', function () {
    expect(DownloadZipUseCase::execute(['2024-01-15']))->toBeInstanceOf(BinaryFileResponse::class);
});

// The zip-failure branch (when $zip->open() returns false) is OS-dependent
// (requires unwritable disk/path) and cannot be reliably triggered in CI.
// The `throw new RuntimeException(...)` line is marked // @codeCoverageIgnore
// in DownloadZipUseCase source file.
