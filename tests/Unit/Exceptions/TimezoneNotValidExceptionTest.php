<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\Exceptions\TimezoneNotValidException;

it('extends InvalidArgumentException', function () {
    expect(new TimezoneNotValidException('Fake/Zone'))
        ->toBeInstanceOf(InvalidArgumentException::class);
});

it('message contains the invalid timezone string', function () {
    expect(new TimezoneNotValidException('Fake/Zone'))
        ->getMessage()->toContain('Fake/Zone');
});
