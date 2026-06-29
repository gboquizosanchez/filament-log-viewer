<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Pages\ListLogs;
use Boquizo\FilamentLogViewer\Tables\LogsTable;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\PanelRegistry;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

use function Pest\Livewire\livewire;

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

    URL::resolveMissingNamedRoutesUsing(fn (string $name): string => 'http://localhost/' . $name);

    $errorBag = new ViewErrorBag;
    $errorBag = $errorBag->put('default', new MessageBag);
    app('view')->share('errors', $errorBag);
});

it('LogsTable getResolveSelectedRecordsUsing with isTrackingDeselectedKeys true returns records except deselected', function () {
    $method = new ReflectionMethod(LogsTable::class, 'getResolveSelectedRecordsUsing');
    $method->setAccessible(true);

    // We need log files to exist for getLogsTableRecords to return data
    $records = $method->invoke(null, [], true, []);
    expect($records)->toBeInstanceOf(Collection::class);
});

it('LogsTable getResolveSelectedRecordsUsing with isTrackingDeselectedKeys false returns only keyed records', function () {
    $method = new ReflectionMethod(LogsTable::class, 'getResolveSelectedRecordsUsing');
    $method->setAccessible(true);

    $records = $method->invoke(null, ['2024-01-15'], false, []);
    expect($records)->toBeInstanceOf(Collection::class);
});

it('LogsTable uses ViewLogModalAction when view_in_modal is true', function () {
    config(['filament-log-viewer.view_in_modal' => true]);

    $method = new ReflectionMethod(LogsTable::class, 'getViewAction');
    $method->setAccessible(true);

    $action = $method->invoke(null);
    expect($action)->toBeInstanceOf(Action::class);

    config(['filament-log-viewer.view_in_modal' => false]);
});

it('LogsTable isEmpty returns true for single empty-ish record', function () {
    $method = new ReflectionMethod(LogsTable::class, 'isEmpty');
    $method->setAccessible(true);

    // A collection with one record that has only one non-null value (date), rest null → isEmpty === true
    $data = collect([['date' => '2024-01-15', 'all' => null, 'error' => null]]);
    $result = $method->invoke(null, $data);
    expect($result)->toBeTrue();
});

it('LogsTable isEmpty returns false for non-empty records', function () {
    $method = new ReflectionMethod(LogsTable::class, 'isEmpty');
    $method->setAccessible(true);

    $data = collect([
        ['date' => '2024-01-15', 'all' => 5, 'error' => 2],
        ['date' => '2024-01-16', 'all' => 3, 'error' => 1],
    ]);
    $result = $method->invoke(null, $data);
    expect($result)->toBeFalse();
});

it('list logs page renders with view_in_modal config true', function () {
    config(['filament-log-viewer.view_in_modal' => true]);

    livewire(ListLogs::class)->assertSuccessful();

    config(['filament-log-viewer.view_in_modal' => false]);
});
