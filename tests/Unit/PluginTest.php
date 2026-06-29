<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\Entities\Log;
use Boquizo\FilamentLogViewer\Exceptions\TimezoneNotValidException;
use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Utils\Stats;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\PanelRegistry;
use Boquizo\FilamentLogViewer\Tests\Unit\FakeGroup;
use Boquizo\FilamentLogViewer\Tests\Unit\FakeGroupLabelled;
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


it('getId() returns plugin identifier', function () {
    expect(FilamentLogViewerPlugin::make()->getId())->toBe('filament-log-viewer');
});

it('driver() returns daily for daily config', function () {
    config(['filament-log-viewer.driver' => 'daily']);
    expect(FilamentLogViewerPlugin::make()->driver())->toBe('daily');
});

it('driver() returns single for single config', function () {
    config(['filament-log-viewer.driver' => 'single']);
    expect(FilamentLogViewerPlugin::make()->driver())->toBe('single');
});

it('driver() returns raw for raw config', function () {
    config(['filament-log-viewer.driver' => 'raw']);
    expect(FilamentLogViewerPlugin::make()->driver())->toBe('raw');
});

it('driver() falls back to daily for unknown config value', function () {
    config(['filament-log-viewer.driver' => 'unknown']);
    expect(FilamentLogViewerPlugin::make()->driver())->toBe('daily');
});

it('isAuthorized() returns true by default', function () {
    expect(FilamentLogViewerPlugin::make()->isAuthorized())->toBeTrue();
});

it('authorize(false) makes isAuthorized() return false', function () {
    expect(FilamentLogViewerPlugin::make()->authorize(false)->isAuthorized())->toBeFalse();
});

it('authorize(closure) evaluates the closure', function () {
    $plugin = FilamentLogViewerPlugin::make()->authorize(fn () => false);
    expect($plugin->isAuthorized())->toBeFalse();
});

it('getNavigationSort() returns integer', function () {
    expect(FilamentLogViewerPlugin::make()->getNavigationSort())->toBeInt();
});

it('navigationSort() setter updates value', function () {
    expect(FilamentLogViewerPlugin::make()->navigationSort(99)->getNavigationSort())->toBe(99);
});

it('getNavigationLabel() returns string', function () {
    expect(FilamentLogViewerPlugin::make()->getNavigationLabel())->toBeString();
});

it('navigationLabel() setter updates label', function () {
    expect(FilamentLogViewerPlugin::make()->navigationLabel('Custom')->getNavigationLabel())
        ->toBe('Custom');
});

it('getNavigationIcon() returns non-null value', function () {
    expect(FilamentLogViewerPlugin::make()->getNavigationIcon())->not->toBeNull();
});

it('navigationIcon() setter updates icon', function () {
    expect(FilamentLogViewerPlugin::make()->navigationIcon('heroicon-o-fire')->getNavigationIcon())
        ->toBe('heroicon-o-fire');
});

it('getNavigationGroup() returns string by default', function () {
    expect(FilamentLogViewerPlugin::make()->getNavigationGroup())->toBeString();
});

it('navigationGroup() with string updates group', function () {
    expect(FilamentLogViewerPlugin::make()->navigationGroup('Admin')->getNavigationGroup())
        ->toBe('Admin');
});

it('navigationGroup(null) returns default translated string', function () {
    expect(FilamentLogViewerPlugin::make()->navigationGroup(null)->getNavigationGroup())
        ->toBeString();
});

it('navigationGroup() with pure UnitEnum returns the enum name', function () {
    $plugin = FilamentLogViewerPlugin::make()->navigationGroup(FakeGroup::Admin);
    expect($plugin->getNavigationGroup())->toBe('Admin');
});

it('navigationGroup() with UnitEnum having getLabel() returns getLabel() value', function () {
    $plugin = FilamentLogViewerPlugin::make()->navigationGroup(FakeGroupLabelled::Admin);
    expect($plugin->getNavigationGroup())->toBe('Admin Label');
});

it('getTimezone() returns app.timezone when not set', function () {
    expect(FilamentLogViewerPlugin::make()->getTimezone())->toBe('UTC');
});

it('timezone() with valid timezone updates and returns it', function () {
    expect(FilamentLogViewerPlugin::make()->timezone('Europe/Madrid')->getTimezone())
        ->toBe('Europe/Madrid');
});

it('timezone() throws TimezoneNotValidException for invalid timezone', function () {
    expect(fn () => FilamentLogViewerPlugin::make()->timezone('Fake/Zone'))
        ->toThrow(TimezoneNotValidException::class);
});

it('getViewerStats() returns Stats instance', function () {
    expect(FilamentLogViewerPlugin::make()->getViewerStats())->toBeInstanceOf(Stats::class);
});

it('getLogsTableRecords() returns array', function () {
    expect(FilamentLogViewerPlugin::make()->getLogsTableRecords())->toBeArray();
});

it('getLogsTableFiltered() returns array for existing date', function () {
    expect(FilamentLogViewerPlugin::make()->getLogsTableFiltered('2024-01-15'))->toBeArray();
});

it('getLogViewerRecord() returns Log for existing date', function () {
    expect(FilamentLogViewerPlugin::make()->getLogViewerRecord('2024-01-15'))
        ->toBeInstanceOf(Log::class);
});

it('clearLog() returns true for existing log', function () {
    $tmp = __DIR__ . '/../fixtures/logs/laravel-2099-09-01.log';
    file_put_contents($tmp, "[2099-09-01 10:00:00] production.INFO: Temp [] []\n");
    expect(FilamentLogViewerPlugin::make()->clearLog('2099-09-01'))->toBeTrue();
    unlink($tmp);
});

it('viewInModal() sets the config flag', function () {
    FilamentLogViewerPlugin::make()->viewInModal(true);
    expect(config('filament-log-viewer.view_in_modal'))->toBeTrue();
});

it('listLogs() setter and getListLog() round-trip', function () {
    $plugin = FilamentLogViewerPlugin::make()->listLogs('SomeClass');
    expect($plugin->getListLog())->toBe('SomeClass');
});

it('viewLog() setter and getViewLog() round-trip', function () {
    $plugin = FilamentLogViewerPlugin::make()->viewLog('SomeClass');
    expect($plugin->getViewLog())->toBe('SomeClass');
});

it('boot() executes without error', function () {
    $panel = Panel::make()->id('test2')->path('test2');
    FilamentLogViewerPlugin::make()->boot($panel);
    expect(true)->toBeTrue();
});

it('deleteLog() returns true for existing log file', function () {
    $tmp = __DIR__ . '/../fixtures/logs/laravel-2099-03-01.log';
    file_put_contents($tmp, "[2099-03-01 10:00:00] production.INFO: Temp [] []\n");
    expect(FilamentLogViewerPlugin::make()->deleteLog('2099-03-01'))->toBeTrue();
});

it('downloadLog() returns BinaryFileResponse for existing log', function () {
    expect(FilamentLogViewerPlugin::make()->downloadLog('2024-01-15'))
        ->toBeInstanceOf(BinaryFileResponse::class);
});

it('downloadLogs() returns BinaryFileResponse for existing logs', function () {
    expect(FilamentLogViewerPlugin::make()->downloadLogs(['2024-01-15']))
        ->toBeInstanceOf(BinaryFileResponse::class);
});
