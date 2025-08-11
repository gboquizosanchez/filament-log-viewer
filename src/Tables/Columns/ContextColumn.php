<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class ContextColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('context')
            ->searchable()
            ->label('')
            ->hidden();
    }
}
