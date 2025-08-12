<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class MessageColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('header')
            ->label(__('filament-log-viewer::log.table.columns.message.label'))
            ->wrap()
            ->searchable()
            ->translateLabel()
            ->sortable();
    }
}
