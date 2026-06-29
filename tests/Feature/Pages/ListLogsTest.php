<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Pages\ListLogs;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\PanelRegistry;
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

it('list logs page renders successfully', function () {
    livewire(ListLogs::class)->assertSuccessful();
});

it('getNavigationLabel() returns string', function () {
    expect(ListLogs::getNavigationLabel())->toBeString();
});

it('getSlug() returns string', function () {
    expect(ListLogs::getSlug())->toBeString();
});

it('canAccess() returns bool', function () {
    expect(ListLogs::canAccess())->toBeBool();
});

it('getNavigationGroup() returns string', function () {
    expect(ListLogs::getNavigationGroup())->toBeString();
});

it('getNavigationSort() returns int or null', function () {
    $sort = ListLogs::getNavigationSort();
    expect($sort === null || is_int($sort))->toBeTrue();
});

it('getNavigationIcon() returns icon value', function () {
    expect(ListLogs::getNavigationIcon())->not->toBeNull();
});

it('getCluster() returns null by default', function () {
    expect(ListLogs::getCluster())->toBeNull();
});

it('page has getTitle() returning string', function () {
    $component = livewire(ListLogs::class)->instance();
    expect($component->getTitle())->toBeString();
});
