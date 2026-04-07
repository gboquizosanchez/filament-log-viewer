<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Filament\Actions\BulkAction;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

class ClearLogBulkAction
{
    public static function make(): BulkAction
    {
        $driver = FilamentLogViewerPlugin::get()->driver();

        return BulkAction::make('clear-logs')
            ->label(
                __('filament-log-viewer::log.table.actions.clear.bulk.label'),
            )
            ->visible($driver === 'single' || Config::boolean('filament-log-viewer.clearable'))
            ->color('warning')
            ->icon(Heroicon::ArchiveBoxXMark)
            ->requiresConfirmation()
            ->modalHeading(
                __('filament-log-viewer::log.table.actions.clear.bulk.label'),
            )
            ->successNotificationTitle(
                __('filament-log-viewer::log.table.actions.clear.bulk.success'),
            )
            ->failureNotificationTitle(
                __('filament-log-viewer::log.table.actions.clear.bulk.error'),
            )
            ->action(self::getAction(...))
            ->deselectRecordsAfterCompletion();
    }

    private static function getAction(Collection $records): void
    {
        $records->each(self::clear(...));
    }

    private static function clear(array $record): bool
    {
        return FilamentLogViewerPlugin::get()->clearLog($record['date']);
    }
}
