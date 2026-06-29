<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\Utils\Level;

it('has exactly 8 enum cases', function () {
    expect(Level::cases())->toHaveCount(8);
});

it('options() returns 9 keys including all', function () {
    expect(Level::options())
        ->toHaveCount(9)
        ->toHaveKey('all')
        ->toHaveKey('error')
        ->toHaveKey('debug');
});

it('options(withoutAll: true) returns 8 keys without all', function () {
    $options = Level::options(withoutAll: true);
    expect($options)->toHaveCount(8)->not->toHaveKey('all');
});

it('label() returns a non-empty translated string', function () {
    expect(Level::Error->label())->toBeString()->not->toBeEmpty();
});

it('all() returns a non-empty string', function () {
    expect(Level::all())->toBeString()->not->toBeEmpty();
});

it('tryFrom returns null for unknown level', function () {
    expect(Level::tryFrom('unknown'))->toBeNull();
});

it('tryFrom returns correct Level instance', function () {
    expect(Level::tryFrom('error'))->toBe(Level::Error);
});

it('ALL constant equals the string "all"', function () {
    expect(Level::ALL)->toBe('all');
});
