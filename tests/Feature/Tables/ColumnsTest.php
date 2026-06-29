<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Pages\ListLogs;
use Boquizo\FilamentLogViewer\Pages\ViewLog;
use Boquizo\FilamentLogViewer\Tables\Columns\ContextColumn;
use Boquizo\FilamentLogViewer\Tables\Columns\EnvColumn;
use Boquizo\FilamentLogViewer\Tables\Columns\LevelColumn;
use Boquizo\FilamentLogViewer\Tables\Columns\MessageColumn;
use Boquizo\FilamentLogViewer\Tables\Columns\NameColumn;
use Boquizo\FilamentLogViewer\Tables\Columns\StackColumn;
use Boquizo\FilamentLogViewer\Utils\Level;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\PanelRegistry;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;

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

it('LevelColumn::make() with no level returns sortable TextColumn', function () {
    expect(LevelColumn::make())->toBeInstanceOf(TextColumn::class);
});

it('LevelColumn::make() with Level::ALL string returns TextColumn', function () {
    expect(LevelColumn::make(Level::ALL))->toBeInstanceOf(TextColumn::class);
});

it('LevelColumn::make() with Level enum returns TextColumn', function () {
    expect(LevelColumn::make(Level::Error))->toBeInstanceOf(TextColumn::class);
});

it('NameColumn::make(date) returns sortable TextColumn', function () {
    expect(NameColumn::make('date'))->toBeInstanceOf(TextColumn::class);
});

it('NameColumn::make(datetime) returns sortable TextColumn', function () {
    expect(NameColumn::make('datetime'))->toBeInstanceOf(TextColumn::class);
});

it('EnvColumn::make() returns TextColumn', function () {
    expect(EnvColumn::make())->toBeInstanceOf(TextColumn::class);
});

it('MessageColumn::make() returns TextColumn', function () {
    expect(MessageColumn::make())->toBeInstanceOf(TextColumn::class);
});

it('StackColumn::make() returns TextColumn', function () {
    expect(StackColumn::make())->toBeInstanceOf(TextColumn::class);
});

it('ContextColumn::make() returns TextColumn', function () {
    expect(ContextColumn::make())->toBeInstanceOf(TextColumn::class);
});

// EnvColumn getColor closure coverage
it('EnvColumn getColor returns danger for production', function () {
    $method = new ReflectionMethod(EnvColumn::class, 'getColor');
    $method->setAccessible(true);
    expect($method->invoke(null, 'production'))->toBe('danger');
});

it('EnvColumn getColor returns orange for staging', function () {
    $method = new ReflectionMethod(EnvColumn::class, 'getColor');
    $method->setAccessible(true);
    expect($method->invoke(null, 'staging'))->toBe('orange');
});

it('EnvColumn getColor returns success for other envs', function () {
    $method = new ReflectionMethod(EnvColumn::class, 'getColor');
    $method->setAccessible(true);
    expect($method->invoke(null, 'local'))->toBe('success');
});

// NameColumn getLabel closure coverage
it('NameColumn getLabel returns date string for daily driver on ListLogs', function () {
    $livewire = Mockery::mock(ListLogs::class);
    $result = NameColumn::getLabel($livewire);
    expect($result)->toBeString();
});

it('NameColumn getLabel returns filename label for non-daily driver', function () {
    // Switch driver to 'single' so $driver !== 'daily' triggers line 33
    config(['filament-log-viewer.driver' => 'single']);

    $livewire = Mockery::mock(ListLogs::class);
    $result = NameColumn::getLabel($livewire);
    expect($result)->toBeString();

    config(['filament-log-viewer.driver' => 'daily']);
});

it('NameColumn getLabel returns HtmlString with timezone when ViewLog and timezone differs', function () {
    // The registered plugin needs a timezone different from app.timezone (UTC)
    // Directly set the timezone on the registered plugin instance
    $plugin = Filament::getPlugin('filament-log-viewer');
    $ref = new ReflectionProperty($plugin, 'timezone');
    $ref->setAccessible(true);
    $ref->setValue($plugin, 'America/New_York');

    $livewire = Mockery::mock(ViewLog::class);

    $result = NameColumn::getLabel($livewire);
    expect($result)->toBeInstanceOf(HtmlString::class);

    // Reset
    $ref->setValue($plugin, null);
});

// NameColumn getFormatStateUsing closure coverage
it('NameColumn getFormatStateUsing returns date from record with date field', function () {
    $record = ['date' => '2024-01-15', 'datetime' => null];
    $result = NameColumn::getFormatStateUsing($record);
    expect($result)->toBe('2024-01-15');
});

it('NameColumn getFormatStateUsing formats datetime when date is null', function () {
    $record = ['date' => null, 'datetime' => '2024-01-15 10:30:45'];
    $result = NameColumn::getFormatStateUsing($record);
    expect($result)->toBeString()->toContain('2024-01-15');
});
