<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Infolists\Components;

use Boquizo\FilamentLogViewer\Utils\Decoder;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\FontFamily;

class ContextTextEntry
{
    public static function make(): TextEntry
    {
        return TextEntry::make('context')
            ->hiddenLabel()
            ->fontFamily(FontFamily::Mono)
            ->html()
            ->extraAttributes([
                'class' => 'overflow-auto',
                'style' => 'max-height: 35rem;',
            ])
            ->hidden(self::getHidden(...))
            ->formatStateUsing(self::getStateUsing(...));
    }

    private static function getHidden(array $record): bool
    {
        return empty($record['context']);
    }

    private static function getStateUsing(array $record): string
    {
        return sprintf(
            '<pre>%s</pre>',
            json_encode(
                Decoder::decode($record['context']),
                JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT,
            ),
        );
    }
}
