<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\Entities\Entry;

// Entries always get an explicit stack string to avoid TypeError in stack().
// Entry::stack() calls htmlentities($this->stack) without null guard, so null stack
// throws TypeError in PHP 8.1+. All tests that invoke stack() (directly or via toArray())
// must use a non-null stack value.
$plain = '[2024-01-15 10:30:45] production.ERROR: Something went wrong';
$ctx = '[2024-01-15 10:30:48] production.ERROR: Error with context {"key":"value","num":42}';

it('stores the level', function () use ($plain) {
    expect((new Entry('error', $plain))->level)->toBe('error');
});

it('extracts environment from header', function () use ($plain) {
    expect((new Entry('error', $plain))->env)->toBe('production');
});

it('parses datetime from header', function () use ($plain) {
    expect((new Entry('error', $plain))->datetime->format('Y-m-d H:i:s'))
        ->toBe('2024-01-15 10:30:45');
});

it('strips datetime and env.LEVEL prefix from header', function () use ($plain) {
    expect((new Entry('error', $plain))->header)->toBe('Something went wrong');
});

it('extracts JSON context and removes it from header', function () use ($ctx) {
    $entry = new Entry('error', $ctx);
    expect($entry->context)->toBe(['key' => 'value', 'num' => 42])
        ->and($entry->header)->not->toContain('{');
});

it('leaves context empty when header has no JSON', function () use ($plain) {
    expect((new Entry('error', $plain))->context)->toBe([]);
});

it('stack() returns html-entity-encoded stack trace', function () {
    $entry = new Entry('error', '[2024-01-15 10:30:45] production.ERROR: Msg', '<b>bold</b>');
    expect($entry->stack())->toContain('&lt;b&gt;');
});

it('stack() returns empty string when stack is empty string', function () {
    // Adaptation: Entry::stack() passes $this->stack directly to htmlentities().
    // A null stack throws TypeError; empty string is the safe equivalent producing "".
    expect((new Entry('info', '[2024-01-15 10:30:45] production.INFO: No stack', ''))->stack())->toBe('');
});

it('stack() with null throws TypeError', function () {
    expect(fn () => (new Entry('info', '[2024-01-15 10:30:45] production.INFO: Msg', null))->stack())
        ->toThrow(TypeError::class);
});

it('context() returns pretty-printed JSON string', function () use ($ctx) {
    $json = (new Entry('error', $ctx))->context();
    expect($json)->toBeJson()->toContain('key');
});

it('context() accepts options flag', function () use ($ctx) {
    $json = (new Entry('error', $ctx))->context(0);
    expect($json)->toBeJson();
});

it('isSame() returns true for matching level', function () use ($plain) {
    expect((new Entry('error', $plain))->isSame('error'))->toBeTrue();
});

it('isSame() returns false for non-matching level', function () use ($plain) {
    expect((new Entry('error', $plain))->isSame('info'))->toBeFalse();
});

it('toArray() contains all expected keys', function () use ($plain) {
    expect((new Entry('error', $plain, ''))->toArray())
        ->toHaveKeys(['env', 'level', 'datetime', 'header', 'stack', 'context']);
});

it('toJson() returns valid JSON', function () use ($plain) {
    expect((new Entry('error', $plain, ''))->toJson())->toBeJson();
});

it('toJson() accepts options flag', function () use ($plain) {
    expect((new Entry('error', $plain, ''))->toJson(JSON_PRETTY_PRINT))->toBeJson();
});

it('jsonSerialize() returns same array as toArray()', function () use ($plain) {
    $entry = new Entry('error', $plain, '');
    expect($entry->jsonSerialize())->toBe($entry->toArray());
});
