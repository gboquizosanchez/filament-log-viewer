<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\Utils\Stats;

$data = [
    '2024-01-15' => [
        'all' => 3,
        'emergency' => 0,
        'alert' => 0,
        'critical' => 0,
        'error' => 2,
        'warning' => 0,
        'notice' => 0,
        'info' => 1,
        'debug' => 0,
    ],
    '2024-01-16' => [
        'all' => 1,
        'emergency' => 0,
        'alert' => 0,
        'critical' => 0,
        'error' => 1,
        'warning' => 0,
        'notice' => 0,
        'info' => 0,
        'debug' => 0,
    ],
];

it('make() returns Stats instance', function () use ($data) {
    expect(Stats::make($data))->toBeInstanceOf(Stats::class);
});

it('rows includes a date key per log', function () use ($data) {
    $stats = Stats::make($data);
    expect($stats->rows)->toHaveKey('2024-01-15')->toHaveKey('2024-01-16');
});

it('each row includes a date field matching its key', function () use ($data) {
    $stats = Stats::make($data);
    expect($stats->rows['2024-01-15']['date'])->toBe('2024-01-15');
});

it('each row includes level counts', function () use ($data) {
    $stats = Stats::make($data);
    expect($stats->rows['2024-01-15']['error'])->toBe(2)
        ->and($stats->rows['2024-01-15']['info'])->toBe(1);
});

it('footer sums counts across all dates', function () use ($data) {
    $stats = Stats::make($data);
    expect($stats->footer['error'])->toBe(3)
        ->and($stats->footer['info'])->toBe(1)
        ->and($stats->footer['all'])->toBe(4);
});

it('totals() returns Collection without all key', function () use ($data) {
    $totals = Stats::make($data)->totals();
    expect($totals)->not->toHaveKey('all')->toHaveKey('error');
});

it('totals() entries contain label, value, color, highlight', function () use ($data) {
    $error = Stats::make($data)->totals()->get('error');
    expect($error)->toHaveKeys(['label', 'value', 'color', 'highlight'])
        ->and($error['value'])->toBe(3);
});
