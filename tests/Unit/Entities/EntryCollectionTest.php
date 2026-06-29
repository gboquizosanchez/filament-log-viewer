<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\Entities\Entry;
use Boquizo\FilamentLogViewer\Entities\EntryCollection;
use Boquizo\FilamentLogViewer\Utils\Level;

$raw = <<<'LOG'
    [2024-01-15 10:30:45] production.ERROR: Error one [] []

    [2024-01-15 10:30:46] production.INFO: Info one [] []

    [2024-01-15 10:30:47] production.ERROR: Error two [] []

    LOG;

it('loads entries from raw log string', function () use ($raw) {
    expect(EntryCollection::load($raw)->count())->toBe(3);
});

it('yields Entry instances', function () use ($raw) {
    expect(EntryCollection::load($raw)->first())->toBeInstanceOf(Entry::class);
});

it('filterByLevel returns only matching entries', function () use ($raw) {
    expect(EntryCollection::load($raw)->filterByLevel('error')->count())->toBe(2);
});

it('filterByLevel for absent level returns empty collection', function () use ($raw) {
    expect(EntryCollection::load($raw)->filterByLevel('emergency')->count())->toBe(0);
});

it('stats() counts per level and total', function () use ($raw) {
    $stats = EntryCollection::load($raw)->stats();
    expect($stats['error'])->toBe(2)
        ->and($stats['info'])->toBe(1)
        ->and($stats[Level::ALL])->toBe(3)
        ->and($stats['emergency'])->toBe(0);
});

it('stats() includes all 9 keys', function () use ($raw) {
    expect(EntryCollection::load($raw)->stats())
        ->toHaveKeys(['all', 'emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug']);
});

it('load with empty string returns empty collection', function () {
    expect(EntryCollection::load('')->count())->toBe(0);
});
