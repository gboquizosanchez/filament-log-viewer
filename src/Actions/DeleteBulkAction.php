<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Pages\ListLogs;
use Filament\Actions\DeleteBulkAction as FilamentDeleteBulkAction;
use Illuminate\Support\Collection;

class DeleteBulkAction
{
    public static function make(): FilamentDeleteBulkAction
    {
        return FilamentDeleteBulkAction::make()
            ->modalHeading(
                __('filament-log-viewer::log.table.actions.delete.bulk.label'),
            )
            ->action(self::getAction(...))
            // I have to set this manually because the default is not working
            ->successRedirectUrl(ListLogs::getUrl());
    }

    private static function getAction(FilamentDeleteBulkAction $action): void
    {
        $action->process(self::processing(...));

        $action->success();
    }

    private static function processing(Collection $records): void
    {
        $records->each(self::delete(...));
    }

    private static function delete(array $record): bool
    {
        return FilamentLogViewerPlugin::get()->deleteLog($record['date']);
    }
}
