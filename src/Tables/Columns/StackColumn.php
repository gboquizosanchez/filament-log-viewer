<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class StackColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('stack')
            ->searchable()
            ->label('')
            ->hidden();
    }
}
