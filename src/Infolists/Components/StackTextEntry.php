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
                'class' => 'overflow-auto',
                'style' => 'max-height: 35rem;',
            ])
            ->hidden(self::getHidden(...))
            ->formatStateUsing(self::getStateUsing(...));
    }

    private static function getHidden(array|object $record): bool
    {
        return empty($record->stack ?? $record['stack']);
    }

    private static function getStateUsing(array|object $record): string
    {
        return preg_replace(
            '/(.*vendor.*$)/m',
            '<span class="text-gray-400">$1</span>',
            nl2br($record->stack ?? $record['stack']),
        );
    }
}
