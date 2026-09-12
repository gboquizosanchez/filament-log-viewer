<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Infolists\Components;

use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\FontFamily;

class StackTextEntry
{
    public static function make(): TextEntry
    {
        return TextEntry::make('stack')
            ->hiddenLabel()
            ->fontFamily(FontFamily::Mono)
            ->html()
            ->extraAttributes([
                'style' => 'max-height: 35rem; overflow: auto;',
            ])
            ->hidden(self::getHidden(...))
            ->formatStateUsing(self::getStateUsing(...));
    }

    /** @param EntryRow $record */
    private static function getHidden(array $record): bool
    {
        return empty($record['stack']);
    }

    /** @param EntryRow $record */
    private static function getStateUsing(array $record): string
    {
        return preg_replace(
            '/(.*vendor.*$)/m',
            '<span class="text-gray-400">$1</span>',
            nl2br($record['stack']),
        ) ?? $record['stack'];
    }
}
