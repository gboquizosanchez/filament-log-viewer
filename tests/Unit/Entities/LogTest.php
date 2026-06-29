<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\Entities\EntryCollection;
use Boquizo\FilamentLogViewer\Entities\Log;
use Illuminate\Support\Carbon;

$path = __DIR__ . '/../../fixtures/logs/laravel-2024-01-15.log';
$raw = static fn () => (string) file_get_contents(
    __DIR__ . '/../../fixtures/logs/laravel-2024-01-15.log'
);

it('make() returns Log instance', function () use ($path, $raw) {
    expect(Log::make('2024-01-15', $path, $raw()))->toBeInstanceOf(Log::class);
});

it('path() returns the given path', function () use ($path, $raw) {
    expect(Log::make('2024-01-15', $path, $raw())->path())->toBe($path);
});

it('file() returns SplFileInfo', function () use ($path, $raw) {
    expect(Log::make('2024-01-15', $path, $raw())->file())->toBeInstanceOf(SplFileInfo::class);
});

it('size() returns non-empty formatted string', function () use ($path, $raw) {
    expect(Log::make('2024-01-15', $path, $raw())->size())->toBeString()->not->toBeEmpty();
});

it('createdAt() returns Carbon', function () use ($path, $raw) {
    expect(Log::make('2024-01-15', $path, $raw())->createdAt())->toBeInstanceOf(Carbon::class);
});

it('updatedAt() returns Carbon', function () use ($path, $raw) {
    expect(Log::make('2024-01-15', $path, $raw())->updatedAt())->toBeInstanceOf(Carbon::class);
});

it('entries() with no level returns all 8 entries', function () use ($path, $raw) {
    $log = Log::make('2024-01-15', $path, $raw());
    expect($log->entries())->toBeInstanceOf(EntryCollection::class)
        ->and($log->entries()->count())->toBe(8);
});

it('entries() with level filters to matching entries', function () use ($path, $raw) {
    expect(Log::make('2024-01-15', $path, $raw())->entries('error')->count())->toBe(1);
});

it('level() returns filtered EntryCollection', function () use ($path, $raw) {
    expect(Log::make('2024-01-15', $path, $raw())->level('info')->count())->toBe(1);
});

it('stats() returns per-level counts', function () use ($path, $raw) {
    $stats = Log::make('2024-01-15', $path, $raw())->stats();
    expect($stats['all'])->toBe(8)->and($stats['error'])->toBe(1);
});

it('toModel() returns array of entry arrays with expected keys', function () use ($path, $raw) {
    $model = Log::make('2024-01-15', $path, $raw())->toModel();
    expect($model)->toBeArray()->not->toBeEmpty()
        ->and($model[0])->toHaveKeys(['env', 'level', 'datetime', 'header', 'stack', 'context']);
});

it('toArray() has date, path, entries keys', function () use ($path, $raw) {
    expect(Log::make('2024-01-15', $path, $raw())->toArray())
        ->toHaveKeys(['date', 'path', 'entries']);
});

it('toJson() returns valid JSON', function () use ($path, $raw) {
    expect(Log::make('2024-01-15', $path, $raw())->toJson())->toBeJson();
});

it('toJson() accepts options flag', function () use ($path, $raw) {
    expect(Log::make('2024-01-15', $path, $raw())->toJson(JSON_PRETTY_PRINT))->toBeJson();
});

it('jsonSerialize() matches toArray()', function () use ($path, $raw) {
    $log = Log::make('2024-01-15', $path, $raw());
    expect($log->jsonSerialize())->toBe($log->toArray());
});
