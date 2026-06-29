<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\Utils\Parser;

$fixture = static fn (): string => (string) file_get_contents(
    __DIR__ . '/../../fixtures/logs/laravel-2024-01-15.log'
);

it('parses all 8 levels from fixture', function () use ($fixture) {
    expect(Parser::parse($fixture()))->toHaveCount(8);
});

it('returns entries in reverse chronological order', function () use ($fixture) {
    $result = Parser::parse($fixture());
    expect($result[0]['level'])->toBe('debug')
        ->and($result[7]['level'])->toBe('emergency');
});

it('parses all 8 level values', function () use ($fixture) {
    $levels = array_column(Parser::parse($fixture()), 'level');
    foreach (['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'] as $level) {
        expect($levels)->toContain($level);
    }
});

it('captures stack trace in stack field', function () use ($fixture) {
    $result = Parser::parse($fixture());
    $error = collect($result)->firstWhere('level', 'error');
    expect($error['stack'])->toContain('#0');
});

it('returns empty array when no log entries match', function () {
    expect(Parser::parse('no log entries here'))->toBeEmpty();
});

it('returns empty array for empty string', function () {
    expect(Parser::parse(''))->toBeEmpty();
});

it('silently drops unknown log levels', function () {
    $raw = "[2024-01-15 10:30:45] production.VERBOSE: Message [] []\n";
    expect(Parser::parse($raw))->toBeEmpty();
});

it('extractDate returns date from log filename', function () {
    expect(Parser::extractDate('laravel-2024-01-15.log'))->toBe('2024-01-15');
});

it('extractDate returns date from bare date string', function () {
    expect(Parser::extractDate('2024-12-31'))->toBe('2024-12-31');
});

it('parse is idempotent — calling twice returns same result', function () use ($fixture) {
    $a = Parser::parse($fixture());
    $b = Parser::parse($fixture());
    expect($a)->toBe($b);
});
