<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Exception;
use Filament\Actions\BulkAction;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadBulkAction
{
    public static function make(): BulkAction
    {
        return BulkAction::make('download')
            ->label(
                __('filament-log-viewer::log.table.actions.download.bulk.label'),
            )
            ->color('success')
            ->icon(Heroicon::ArrowDownTray)
            ->requiresConfirmation()
            ->modalHeading(
                __('filament-log-viewer::log.table.actions.download.bulk.label'),
            )
            ->failureNotificationTitle(
                __('filament-log-viewer::log.table.actions.download.bulk.error'),
            )
            ->action(self::getAction(...));
    }

    /** @param Collection<int, LogRow> $records */
    private static function getAction(
        BulkAction $action,
        Collection $records,
    ): ?BinaryFileResponse {
        try {
            $logs = array_values($records
                ->map(static fn (array $record): string => $record['date'])
                ->values()
                ->all());

            return FilamentLogViewerPlugin::get()->downloadLogs($logs);
        } catch (Exception) {
            $action->failure();

            return null;
        }
    }
}
