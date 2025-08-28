<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class EnvColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('env')
            ->badge()
            ->color(self::getColor(...));
    }

    private static function getColor(string $state): string
    {
        return match ($state) {
            'production' => 'danger',
            'staging' => 'orange',
            default => 'success',
        };
    }
}
