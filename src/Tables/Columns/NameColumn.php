<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Tables\Columns;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Filament\Tables\Columns\TextColumn;

class NameColumn
{
    public static function make(string $name): TextColumn
    {
        return TextColumn::make($name)
            ->label(self::getLabel(...))
            ->hidden(FilamentLogViewerPlugin::get()->driver() === 'stack')
            ->searchable()
            ->sortable();
    }

    public static function getLabel(): string
    {
        $driver = FilamentLogViewerPlugin::get()->driver();

        if ($driver !== 'daily') {
            return __('filament-log-viewer::log.table.columns.filename.label');
        }

        return __('filament-log-viewer::log.table.columns.date.label');
    }
}
