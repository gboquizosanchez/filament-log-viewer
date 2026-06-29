<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\Utils\Icons;
use Filament\Support\Enums\IconSize;
use Illuminate\Support\HtmlString;

it('Icons::get() returns HtmlString for a valid level icon', function () {
    expect(Icons::get('error', IconSize::Medium))->toBeInstanceOf(HtmlString::class);
});

it('Icons::get() works for every IconSize', function () {
    foreach (IconSize::cases() as $size) {
        expect(Icons::get('info', $size))->toBeInstanceOf(HtmlString::class);
    }
});
