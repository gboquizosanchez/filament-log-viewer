<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Boquizo\FilamentLogViewer\Actions\Concerns\ResolvesLogRecord;
use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Pages\ListLogs;
use Boquizo\FilamentLogViewer\Pages\ViewLog;
use Boquizo\FilamentLogViewer\UseCases\ParseDateUseCase;
use Exception;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Config;

class ClearLogAction
{
    use ResolvesLogRecord;

    public static function make(bool $withTooltip = false): Action
    {
        $driver = FilamentLogViewerPlugin::get()->driver();

        $action = Action::make('clear-logs')
            ->hiddenLabel()
            ->button()
            ->visible($driver === 'single' || Config::boolean('filament-log-viewer.clearable'))
            ->label(__('filament-log-viewer::log.table.actions.clear.label'))
            ->modalHeading(self::getTitle(...))
            ->color('warning')
            ->successNotificationTitle(
                __('filament-log-viewer::log.table.actions.clear.success'),
            )
            ->failureNotificationTitle(
                __('filament-log-viewer::log.table.actions.clear.error'),
            )
            ->icon(Heroicon::ArchiveBoxXMark)
            ->requiresConfirmation()
            ->action(self::getAction(...));

        if ($withTooltip) {
            $action->tooltip(self::getTitle(...));
        }

        return $action;
    }

    private static function getTitle(
        Action $action,
        ViewLog | ListLogs $livewire,
    ): string {
        $date = self::resolveLogDate($action, $livewire);

        return __('filament-log-viewer::log.table.actions.clear.label', [
            'log' => ParseDateUseCase::execute($date),
        ]);
    }

    private static function getAction(
        Action $action,
        ViewLog | ListLogs $livewire,
    ): void {
        try {
            $date = self::resolveLogDate($action, $livewire);

            FilamentLogViewerPlugin::get()->clearLog($date);
        } catch (Exception) {
            $action->failure();
        }
    }
}
