<?php

declare(strict_types=1);

use Boquizo\FilamentLogViewer\Utils\Decoder;

it('returns plain string unchanged', function () {
    expect(Decoder::decode('plain string'))->toBe('plain string');
});

it('decodes a valid JSON object string into array', function () {
    expect(Decoder::decode('{"key":"value"}'))->toBe(['key' => 'value']);
});

it('does not decode a JSON scalar string', function () {
    // json_decode('"hello"') returns "hello" (a string, not array/object) — must be left as-is
    expect(Decoder::decode('"hello"'))->toBe('"hello"');
});

it('recursively decodes JSON strings nested in arrays', function () {
    expect(Decoder::decode(['nested' => '{"key":"value"}']))->toBe(['nested' => ['key' => 'value']]);
});

it('recursively decodes JSON strings nested in objects', function () {
    $obj = (object) ['nested' => '{"key":"value"}'];
    $result = Decoder::decode($obj);
    expect($result)->toBeObject()
        ->and($result->nested)->toBe(['key' => 'value']);
});

it('returns integer unchanged', function () {
    expect(Decoder::decode(42))->toBe(42);
});

it('returns null unchanged', function () {
    expect(Decoder::decode(null))->toBeNull();
});

it('returns bool unchanged', function () {
    expect(Decoder::decode(true))->toBeTrue()
        ->and(Decoder::decode(false))->toBeFalse();
});

it('returns invalid JSON string unchanged', function () {
    expect(Decoder::decode('{not valid json}'))->toBe('{not valid json}');
});

it('decodes nested JSON recursively — two levels deep', function () {
    $result = Decoder::decode(['a' => '{"b":"{\\"c\\":1}"}']);
    // First decode: "a" → ['b' => '{"c":1}']
    // Second decode: 'b' → ['c' => 1]
    expect($result['a']['b'])->toBe(['c' => 1]);
});
