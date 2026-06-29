<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Pages\ViewLog;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\PanelRegistry;
use Illuminate\Support\Facades\URL;
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

    app('view')->share('errors', new ViewErrorBag);
});

it('view log page mounts and renders for existing date', function () {
    livewire(ViewLog::class, ['record' => '2024-01-15'])->assertSuccessful();
});

it('mount aborts with 404 for unknown date', function () {
    livewire(ViewLog::class, ['record' => '1999-01-01'])
        ->assertStatus(404);
});

it('getSlug() includes {record} placeholder', function () {
    expect(ViewLog::getSlug())->toContain('{record}');
});

it('canAccess() returns bool', function () {
    expect(ViewLog::canAccess())->toBeBool();
});

it('getDefaultActiveTab() returns all', function () {
    $component = livewire(ViewLog::class, ['record' => '2024-01-15'])->instance();
    expect($component->getDefaultActiveTab())->toBe('all');
});

it('getTabs() returns array', function () {
    $component = livewire(ViewLog::class, ['record' => '2024-01-15'])->instance();
    expect($component->getTabs())->toBeArray();
});

it('getTitle() returns non-empty string', function () {
    $component = livewire(ViewLog::class, ['record' => '2024-01-15'])->instance();
    expect($component->getTitle())->toBeString()->not->toBeEmpty();
});

it('getTabs() returns empty array when only one level has entries matching all count', function () {
    // laravel-2024-01-01.log has only ERROR entries.
    // all=1, error=1, others=0. in_array(1, [1,0,0, ...]) = true, so getTabs returns [].
    $component = livewire(ViewLog::class, ['record' => '2024-01-01'])->instance();
    $tabs = $component->getTabs();
    expect($tabs)->toBeArray()->toBeEmpty();
});

it('back header action executes and redirects', function () {
    // Calling the back action should execute BackAction::getAction (the redirect closure)
    livewire(ViewLog::class, ['record' => '2024-01-15'])
        ->callAction('back')
        ->assertRedirect();
});
