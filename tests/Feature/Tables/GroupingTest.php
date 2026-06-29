<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\Tables\Grouping\LevelGroup;
use Filament\Tables\Grouping\Group;

it('LevelGroup::make() returns Group', function () {
    expect(LevelGroup::make())->toBeInstanceOf(Group::class);
});

it('LevelGroup getTitle returns level label from record', function () {
    $method = new ReflectionMethod(LevelGroup::class, 'getTitle');
    $method->setAccessible(true);

    $record = ['level' => 'error'];
    $result = $method->invoke(null, $record);
    expect($result)->toBeString()->not->toBeEmpty();
});
