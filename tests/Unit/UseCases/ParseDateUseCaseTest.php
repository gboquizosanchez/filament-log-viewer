<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\UseCases\ParseDateUseCase;

// ParseDateUseCase does not call FilamentLogViewerPlugin::get(), so no panel setup needed.

it('formats a valid ISO date string', function () {
    $result = ParseDateUseCase::execute('2024-01-15');
    expect($result)->toBeString()->not->toBeEmpty();
});

it('returns a string for null input without throwing', function () {
    // Carbon::parse(null) parses as "now" — no exception is thrown
    $result = ParseDateUseCase::execute(null);
    expect($result)->toBeString();
});

it('returns a string for another valid date', function () {
    $result = ParseDateUseCase::execute('2024-01-16');
    expect($result)->toBeString()->not->toBeEmpty();
});

it('returns the original string when Carbon throws InvalidFormatException', function () {
    // 'not-a-date' triggers Carbon\Exceptions\InvalidFormatException
    $result = ParseDateUseCase::execute('not-a-date');
    expect($result)->toBe('not-a-date');
});
