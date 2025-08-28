<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Pages\ListLogs;
use Boquizo\FilamentLogViewer\Pages\ViewLog;
use Boquizo\FilamentLogViewer\UseCases\ParseDateUseCase;
use Exception;
use Filament\Actions\Action;
use Filament\Tables\Actions\Action as TableAction;
use Illuminate\Support\Facades\Config;

class ClearLogAction
{
    public static function make(bool $withTooltip = false): Action|TableAction
    {
        $driver = FilamentLogViewerPlugin::get()->driver();

        $action = self::resolveAction($withTooltip)
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
            ->action(self::getAction(...));

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

        $date = $model?->date;

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

            $date = $model?->date;

            FilamentLogViewerPlugin::get()->clearLog($date);
        } catch (Exception) {
            $action->failure();
        }
    }

    private static function resolveAction(bool $withTooltip): Action|TableAction
    {
        if ($withTooltip) {
            return Action::make('clear-logs');
        }

        return TableAction::make('clear-logs');
    }
}
