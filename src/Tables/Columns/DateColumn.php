<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class DateColumn
{
    public static function make(string $name): TextColumn
    {
        return TextColumn::make($name)
            ->label(__('filament-log-viewer::log.table.columns.date.label'))
            ->searchable()
            ->sortable();
    }
}
