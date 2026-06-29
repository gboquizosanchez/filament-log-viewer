<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\Infolists\Components\ContextTextEntry;
use Boquizo\FilamentLogViewer\Infolists\Components\StackTextEntry;
use Filament\Infolists\Components\TextEntry;

it('ContextTextEntry::make() returns TextEntry', function () {
    expect(ContextTextEntry::make())->toBeInstanceOf(TextEntry::class);
});

it('StackTextEntry::make() returns TextEntry', function () {
    expect(StackTextEntry::make())->toBeInstanceOf(TextEntry::class);
});

it('ContextTextEntry getHidden returns true when context is empty', function () {
    $method = new ReflectionMethod(ContextTextEntry::class, 'getHidden');
    $method->setAccessible(true);

    $record = (object) ['context' => ''];
    expect($method->invoke(null, $record))->toBeTrue();
});

it('ContextTextEntry getHidden returns false when context is present', function () {
    $method = new ReflectionMethod(ContextTextEntry::class, 'getHidden');
    $method->setAccessible(true);

    $record = (object) ['context' => '{"key":"value"}'];
    expect($method->invoke(null, $record))->toBeFalse();
});

it('ContextTextEntry getStateUsing returns pre-formatted JSON', function () {
    $method = new ReflectionMethod(ContextTextEntry::class, 'getStateUsing');
    $method->setAccessible(true);

    $record = (object) ['context' => '{"key":"value"}'];
    $result = $method->invoke(null, $record);
    expect($result)->toStartWith('<pre>')->toContain('key');
});

it('StackTextEntry getHidden returns true when stack is empty', function () {
    $method = new ReflectionMethod(StackTextEntry::class, 'getHidden');
    $method->setAccessible(true);

    $record = (object) ['stack' => ''];
    expect($method->invoke(null, $record))->toBeTrue();
});

it('StackTextEntry getHidden returns false when stack is present', function () {
    $method = new ReflectionMethod(StackTextEntry::class, 'getHidden');
    $method->setAccessible(true);

    $record = (object) ['stack' => '#0 /app/Handler.php(50)'];
    expect($method->invoke(null, $record))->toBeFalse();
});

it('StackTextEntry getStateUsing wraps vendor lines in gray span', function () {
    $method = new ReflectionMethod(StackTextEntry::class, 'getStateUsing');
    $method->setAccessible(true);

    $record = (object) ['stack' => "#0 /vendor/laravel/framework/Kernel.php(168)\n#1 /app/Handler.php(50)"];
    $result = $method->invoke(null, $record);
    expect($result)->toContain('text-gray-400')->toContain('vendor');
});
