<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Exception;
use Filament\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
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
            ->icon('fas-download')
            ->requiresConfirmation()
            ->modalHeading(
                __('filament-log-viewer::log.table.actions.download.bulk.label'),
            )
            ->failureNotificationTitle(
                __('filament-log-viewer::log.table.actions.download.bulk.error'),
            )
            ->action(self::getAction(...));
    }

    private static function getAction(
        BulkAction $action,
        Collection $records,
    ): ?BinaryFileResponse {
        try {
            $logs = $records->pluck('date')->all();

            return FilamentLogViewerPlugin::get()->downloadLogs($logs);
        } catch (Exception) {
            $action->failure();

            return null;
        }
    }
}
