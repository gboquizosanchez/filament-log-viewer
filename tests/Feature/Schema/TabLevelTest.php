<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\Schema\Components\TabLevel;
use Boquizo\FilamentLogViewer\Utils\Level;
use Filament\Schemas\Components\Tabs\Tab;

it('TabLevel::make() with Level enum returns Tab', function () {
    expect(TabLevel::make(Level::Error))->toBeInstanceOf(Tab::class);
});

it('TabLevel::make() with string all returns Tab', function () {
    expect(TabLevel::make(Level::ALL))->toBeInstanceOf(Tab::class);
});
