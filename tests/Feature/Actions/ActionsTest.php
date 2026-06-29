<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\Actions\BackAction;
use Boquizo\FilamentLogViewer\Actions\ClearLogAction;
use Boquizo\FilamentLogViewer\Actions\ClearLogBulkAction;
use Boquizo\FilamentLogViewer\Actions\ContextAction;
use Boquizo\FilamentLogViewer\Actions\DeleteAction;
use Boquizo\FilamentLogViewer\Actions\DeleteBulkAction;
use Boquizo\FilamentLogViewer\Actions\DownloadAction;
use Boquizo\FilamentLogViewer\Actions\DownloadBulkAction;
use Boquizo\FilamentLogViewer\Actions\StackAction;
use Boquizo\FilamentLogViewer\Actions\ViewLogAction;
use Boquizo\FilamentLogViewer\Actions\ViewLogModalAction;
use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Pages\ListLogs;
use Boquizo\FilamentLogViewer\Pages\ViewLog;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteAction as FilamentDeleteAction;
use Filament\Actions\DeleteBulkAction as FilamentDeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\PanelRegistry;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
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

    URL::resolveMissingNamedRoutesUsing(fn (string $name): string => 'http://localhost/' . $name);
});

it('BackAction::make() returns Action', function () {
    expect(BackAction::make())->toBeInstanceOf(Action::class);
});

it('ClearLogAction::make() returns Action', function () {
    expect(ClearLogAction::make())->toBeInstanceOf(Action::class);
});

it('ClearLogAction::make(withTooltip: true) returns Action', function () {
    expect(ClearLogAction::make(withTooltip: true))->toBeInstanceOf(Action::class);
});

it('ClearLogBulkAction::make() returns BulkAction', function () {
    expect(ClearLogBulkAction::make())->toBeInstanceOf(BulkAction::class);
});

it('ContextAction::make() returns Action', function () {
    expect(ContextAction::make())->toBeInstanceOf(Action::class);
});

it('DeleteAction::make() returns Action', function () {
    expect(DeleteAction::make())->toBeInstanceOf(Action::class);
});

it('DeleteAction::make(withTooltip: true) returns Action', function () {
    expect(DeleteAction::make(withTooltip: true))->toBeInstanceOf(Action::class);
});

it('DeleteBulkAction::make() returns BulkAction', function () {
    expect(DeleteBulkAction::make())->toBeInstanceOf(BulkAction::class);
});

it('DownloadAction::make() returns Action', function () {
    expect(DownloadAction::make())->toBeInstanceOf(Action::class);
});

it('DownloadAction::make(withTooltip: true) returns Action', function () {
    expect(DownloadAction::make(withTooltip: true))->toBeInstanceOf(Action::class);
});

it('DownloadBulkAction::make() returns BulkAction', function () {
    expect(DownloadBulkAction::make())->toBeInstanceOf(BulkAction::class);
});

it('StackAction::make() returns Action', function () {
    expect(StackAction::make())->toBeInstanceOf(Action::class);
});

it('ViewLogAction::make() returns ViewAction', function () {
    expect(ViewLogAction::make())->toBeInstanceOf(ViewAction::class);
});

it('ViewLogModalAction::make() returns Action', function () {
    expect(ViewLogModalAction::make())->toBeInstanceOf(Action::class);
});

// ---- Closure coverage via reflection ----

// BackAction::getAction is covered by the Livewire callAction test in ViewLogTest.php

// ClearLogAction::getTitle
it('ClearLogAction getTitle returns string with date from record array', function () {
    $method = new ReflectionMethod(ClearLogAction::class, 'getTitle');
    $method->setAccessible(true);

    $action = Mockery::mock(Action::class);
    $action->shouldReceive('getRecord')->andReturn(['date' => '2024-01-15']);

    $livewire = Mockery::mock(ListLogs::class);

    $result = $method->invoke(null, $action, $livewire);
    expect($result)->toBeString()->toContain('2024');
});

it('ClearLogAction getTitle uses livewire record when action has no record', function () {
    $method = new ReflectionMethod(ClearLogAction::class, 'getTitle');
    $method->setAccessible(true);

    $action = Mockery::mock(Action::class);
    $action->shouldReceive('getRecord')->andReturn(null);

    $livewire = Mockery::mock(ViewLog::class);
    $livewire->record = (object) ['date' => '2024-01-15'];

    $result = $method->invoke(null, $action, $livewire);
    expect($result)->toBeString()->toContain('2024');
});

// ClearLogAction::getAction
it('ClearLogAction getAction calls clearLog on plugin', function () {
    $tmp = __DIR__ . '/../../fixtures/logs/laravel-2099-11-01.log';
    file_put_contents($tmp, "[2099-11-01 10:00:00] production.INFO: Temp [] []\n");

    $method = new ReflectionMethod(ClearLogAction::class, 'getAction');
    $method->setAccessible(true);

    $action = Mockery::mock(Action::class);
    $action->shouldReceive('getRecord')->andReturn(['date' => '2099-11-01']);
    $action->shouldNotReceive('failure');

    $livewire = Mockery::mock(ListLogs::class);

    $method->invoke(null, $action, $livewire);

    expect(file_exists($tmp))->toBeTrue();
    @unlink($tmp);
});

it('ClearLogAction getAction calls failure on exception', function () {
    $method = new ReflectionMethod(ClearLogAction::class, 'getAction');
    $method->setAccessible(true);

    $action = Mockery::mock(Action::class);
    // Throw an exception from getRecord to trigger the catch block
    $action->shouldReceive('getRecord')->andThrow(new Exception('forced error'));
    $action->shouldReceive('failure')->once();

    $livewire = Mockery::mock(ListLogs::class);

    $method->invoke(null, $action, $livewire);
});

// ClearLogBulkAction::getAction / clear
it('ClearLogBulkAction getAction processes records collection', function () {
    $tmp = __DIR__ . '/../../fixtures/logs/laravel-2099-11-02.log';
    file_put_contents($tmp, "[2099-11-02 10:00:00] production.INFO: Temp [] []\n");

    $method = new ReflectionMethod(ClearLogBulkAction::class, 'getAction');
    $method->setAccessible(true);

    $records = collect([['date' => '2099-11-02']]);
    $method->invoke(null, $records);

    @unlink($tmp);
    expect(true)->toBeTrue();
});

it('ClearLogBulkAction clear calls clearLog on plugin', function () {
    $tmp = __DIR__ . '/../../fixtures/logs/laravel-2099-11-03.log';
    file_put_contents($tmp, "[2099-11-03 10:00:00] production.INFO: Temp [] []\n");

    $method = new ReflectionMethod(ClearLogBulkAction::class, 'clear');
    $method->setAccessible(true);

    $result = $method->invoke(null, ['date' => '2099-11-03']);
    expect($result)->toBeBool();

    @unlink($tmp);
});

// DeleteAction::getTitle
it('DeleteAction getTitle returns string with date from record array', function () {
    $method = new ReflectionMethod(DeleteAction::class, 'getTitle');
    $method->setAccessible(true);

    $action = Mockery::mock(FilamentDeleteAction::class);
    $action->shouldReceive('getRecord')->andReturn(['date' => '2024-01-15']);

    $livewire = Mockery::mock(ListLogs::class);

    $result = $method->invoke(null, $action, $livewire);
    expect($result)->toBeString()->toContain('2024');
});

// DeleteAction::getAction
it('DeleteAction getAction calls deleteLog on plugin', function () {
    $tmp = __DIR__ . '/../../fixtures/logs/laravel-2099-01-04.log';
    file_put_contents($tmp, "[2099-01-04 10:00:00] production.INFO: Temp [] []\n");

    $method = new ReflectionMethod(DeleteAction::class, 'getAction');
    $method->setAccessible(true);

    $action = Mockery::mock(FilamentDeleteAction::class);
    $action->shouldReceive('getRecord')->andReturn(['date' => '2099-01-04']);
    $action->shouldNotReceive('failure');

    $livewire = Mockery::mock(ListLogs::class);

    $method->invoke(null, $action, $livewire);
    expect(file_exists($tmp))->toBeFalse();
});

it('DeleteAction getAction calls failure on exception', function () {
    $method = new ReflectionMethod(DeleteAction::class, 'getAction');
    $method->setAccessible(true);

    $action = Mockery::mock(FilamentDeleteAction::class);
    $action->shouldReceive('getRecord')->andReturn(['date' => 'nonexistent-date']);
    $action->shouldReceive('failure')->once();

    $livewire = Mockery::mock(ListLogs::class);

    $method->invoke(null, $action, $livewire);
});

// DeleteBulkAction::getAction / processing / delete
it('DeleteBulkAction getAction processes and succeeds', function () {
    $tmp = __DIR__ . '/../../fixtures/logs/laravel-2099-01-05.log';
    file_put_contents($tmp, "[2099-01-05 10:00:00] production.INFO: Temp [] []\n");

    $method = new ReflectionMethod(DeleteBulkAction::class, 'getAction');
    $method->setAccessible(true);

    $processed = false;
    $action = Mockery::mock(FilamentDeleteBulkAction::class);
    $action->shouldReceive('process')->once()->andReturnUsing(function ($callback) use (&$processed) {
        $callback(collect([['date' => '2099-01-05']]));
        $processed = true;
    });
    $action->shouldReceive('success')->once();

    $method->invoke(null, $action);
    expect($processed)->toBeTrue();
    expect(file_exists($tmp))->toBeFalse();
});

it('DeleteBulkAction delete calls deleteLog on plugin', function () {
    $tmp = __DIR__ . '/../../fixtures/logs/laravel-2099-01-06.log';
    file_put_contents($tmp, "[2099-01-06 10:00:00] production.INFO: Temp [] []\n");

    $method = new ReflectionMethod(DeleteBulkAction::class, 'delete');
    $method->setAccessible(true);

    $result = $method->invoke(null, ['date' => '2099-01-06']);
    expect($result)->toBeBool();
    expect(file_exists($tmp))->toBeFalse();
});

// DownloadAction::getTitle
it('DownloadAction getTitle returns string with date from record', function () {
    $method = new ReflectionMethod(DownloadAction::class, 'getTitle');
    $method->setAccessible(true);

    $action = Mockery::mock(Action::class);
    $action->shouldReceive('getRecord')->andReturn(['date' => '2024-01-15']);

    $livewire = Mockery::mock(ListLogs::class);

    $result = $method->invoke(null, $action, $livewire);
    expect($result)->toBeString()->toContain('2024');
});

// DownloadAction::getAction
it('DownloadAction getAction returns BinaryFileResponse', function () {
    $method = new ReflectionMethod(DownloadAction::class, 'getAction');
    $method->setAccessible(true);

    $action = Mockery::mock(Action::class);
    $action->shouldReceive('getRecord')->andReturn(['date' => '2024-01-15']);

    $livewire = Mockery::mock(ListLogs::class);

    $response = $method->invoke(null, $action, $livewire);
    expect($response)->toBeInstanceOf(BinaryFileResponse::class);
});

// DownloadBulkAction::getAction
it('DownloadBulkAction getAction returns BinaryFileResponse for multiple records', function () {
    $method = new ReflectionMethod(DownloadBulkAction::class, 'getAction');
    $method->setAccessible(true);

    $action = Mockery::mock(BulkAction::class);
    $action->shouldNotReceive('failure');

    $records = collect([['date' => '2024-01-15'], ['date' => '2024-01-16']]);

    $response = $method->invoke(null, $action, $records);
    expect($response)->toBeInstanceOf(BinaryFileResponse::class);
});

it('DownloadBulkAction getAction calls failure on exception', function () {
    $method = new ReflectionMethod(DownloadBulkAction::class, 'getAction');
    $method->setAccessible(true);

    $action = Mockery::mock(BulkAction::class);
    $action->shouldReceive('failure')->once();

    // Pass records with non-existent dates to trigger failure
    $records = collect([['date' => 'nonexistent-date-xyz']]);

    $result = $method->invoke(null, $action, $records);
    expect($result)->toBeNull();
});

// ViewLogModalAction private closures
it('ViewLogModalAction getRecordDate returns date from array record', function () {
    $method = new ReflectionMethod(ViewLogModalAction::class, 'getRecordDate');
    $method->setAccessible(true);

    $action = Mockery::mock(Action::class);
    $action->shouldReceive('getRecord')->andReturn(['date' => '2024-01-15']);

    $result = $method->invoke(null, $action);
    expect($result)->toBe('2024-01-15');
});

it('ViewLogModalAction getRecordDate returns date from object record', function () {
    $method = new ReflectionMethod(ViewLogModalAction::class, 'getRecordDate');
    $method->setAccessible(true);

    // Use an Eloquent model mock since Action::getRecord() typed as Model|array|null
    $model = Mockery::mock(Model::class)->makePartial();
    $model->date = '2024-01-15';

    $action = Mockery::mock(Action::class);
    $action->shouldReceive('getRecord')->andReturn($model);

    $result = $method->invoke(null, $action);
    expect($result)->toBe('2024-01-15');
});

it('ViewLogModalAction getRecordDate returns empty string when record is null', function () {
    $method = new ReflectionMethod(ViewLogModalAction::class, 'getRecordDate');
    $method->setAccessible(true);

    $action = Mockery::mock(Action::class);
    $action->shouldReceive('getRecord')->andReturn(null);

    $result = $method->invoke(null, $action);
    expect($result)->toBe('');
});

it('ViewLogModalAction getHeading returns parsed date string', function () {
    $method = new ReflectionMethod(ViewLogModalAction::class, 'getHeading');
    $method->setAccessible(true);

    $action = Mockery::mock(Action::class);
    $action->shouldReceive('getRecord')->andReturn(['date' => '2024-01-15']);

    $result = $method->invoke(null, $action);
    expect($result)->toBeString();
});

it('ViewLogModalAction getModalContent returns View for existing log', function () {
    $method = new ReflectionMethod(ViewLogModalAction::class, 'getModalContent');
    $method->setAccessible(true);

    $action = Mockery::mock(Action::class);
    $action->shouldReceive('getRecord')->andReturn(['date' => '2024-01-15']);

    $result = $method->invoke(null, $action);
    expect($result)->toBeInstanceOf(View::class);
});
