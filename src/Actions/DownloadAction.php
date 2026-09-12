<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Boquizo\FilamentLogViewer\Actions\Concerns\ResolvesLogRecord;
use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Pages\ListLogs;
use Boquizo\FilamentLogViewer\Pages\ViewLog;
use Boquizo\FilamentLogViewer\UseCases\ParseDateUseCase;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadAction
{
    use ResolvesLogRecord;

    public static function make(bool $withTooltip = false): Action
    {
        $action = Action::make('download')
            ->hiddenLabel()
            ->button()
            ->label(__('filament-log-viewer::log.table.actions.download.label'))
            ->modalHeading(self::getTitle(...))
            ->color('success')
            ->icon(Heroicon::ArrowDownTray)
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

        return __('filament-log-viewer::log.table.actions.download.label', [
            'log' => ParseDateUseCase::execute($date),
        ]);
    }

    private static function getAction(
        Action $action,
        ViewLog | ListLogs $livewire,
    ): BinaryFileResponse {
        return FilamentLogViewerPlugin::get()->downloadLog(
            self::resolveLogDate($action, $livewire),
        );
    }
}
