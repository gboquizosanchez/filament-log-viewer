<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Tables\Columns;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Pages\ListLogs;
use Boquizo\FilamentLogViewer\Pages\ViewLog;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\HtmlString;

class NameColumn
{
    public static function make(string $name): TextColumn
    {
        return TextColumn::make($name)
            ->label(self::getLabel(...))
            ->hidden(FilamentLogViewerPlugin::get()->driver() === 'single')
            ->formatStateUsing(self::getFormatStateUsing(...))
            ->searchable()
            ->sortable();
    }

    public static function getLabel(ListLogs | ViewLog $livewire): string | HtmlString
    {
        $driver = FilamentLogViewerPlugin::get()->driver();
        $timezone = FilamentLogViewerPlugin::get()->getTimezone();

        if ($driver !== 'daily') {
            return __('filament-log-viewer::log.table.columns.filename.label');
        }

        $date = __('filament-log-viewer::log.table.columns.date.label');

        if ($livewire instanceof ViewLog && $timezone !== Config::string('app.timezone')) {
            return new HtmlString(
                "{$date} <small class='text-gray-500'>({$timezone})</small>",
            );
        }

        return $date;
    }

    /** @param LogRow|EntryRow $record */
    public static function getFormatStateUsing(array $record): string
    {
        if (array_key_exists('date', $record)) {
            return $record['date'];
        }

        $timezone = FilamentLogViewerPlugin::get()->getTimezone();

        return Carbon::parse($record['datetime'])
            ->timezone($timezone)
            ->format('Y-m-d H:i:s');
    }
}
