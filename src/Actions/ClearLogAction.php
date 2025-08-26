<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Pages\ListLogs;
use Boquizo\FilamentLogViewer\Pages\ViewLog;
use Boquizo\FilamentLogViewer\UseCases\ParseDateUseCase;
use Exception;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Config;

class ClearLogAction
{
    public static function make(bool $withTooltip = false): Action
    {
        $driver = FilamentLogViewerPlugin::get()->driver();

        $action = Action::make('clear-logs')
            ->hiddenLabel()
            ->button()
            ->visible($driver === 'stack' || Config::boolean('filament-log-viewer.clearable'))
            ->label(__('filament-log-viewer::log.table.actions.clear.label'))
            ->modalHeading(self::getTitle(...))
            ->color('warning')
            ->successNotificationTitle(
                __('filament-log-viewer::log.table.actions.clear.success'),
            )
            ->failureNotificationTitle(
                __('filament-log-viewer::log.table.actions.clear.error'),
            )
            ->icon('fas-broom')
            ->requiresConfirmation()
            ->action(self::getAction(...))
            // I have to set this manually because the default is not working
            ->successRedirectUrl(ListLogs::getUrl());

        if ($withTooltip) {
            $action->tooltip(__('filament-log-viewer::log.table.actions.clear.label'));
        }

        return $action;
    }

    private static function getTitle(
        Action $action,
        ViewLog|ListLogs $livewire,
    ): string {
        $model = $action->getRecord() ?? $livewire->record;

        $date = $model?->date ?? $model['date'];

        return __('filament-log-viewer::log.table.actions.clear.label', [
            'log' => ParseDateUseCase::execute($date),
        ]);
    }


    private static function getAction(
        Action $action,
        ViewLog|ListLogs $livewire,
    ): void {
        try {
            $model = $action->getRecord() ?? $livewire->record;

            $date = $model?->date ?? $model['date'];

            FilamentLogViewerPlugin::get()->clearLog($date);
        } catch (Exception) {
            $action->failure();
        }
    }
}
