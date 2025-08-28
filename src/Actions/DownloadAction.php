<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Pages\ListLogs;
use Boquizo\FilamentLogViewer\Pages\ViewLog;
use Boquizo\FilamentLogViewer\UseCases\ParseDateUseCase;
use Filament\Actions\Action;
use Filament\Tables\Actions\Action as TableAction;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadAction
{
    public static function make(bool $withTooltip = false): Action|TableAction
    {
        $action = self::resolveAction($withTooltip)
            ->hiddenLabel()
            ->button()
            ->label(__('filament-log-viewer::log.table.actions.download.label'))
            ->modalHeading(self::getTitle(...))
            ->color('success')
            ->icon('fas-download')
            ->requiresConfirmation()
            ->action(self::getAction(...));

        if ($withTooltip) {
            $action->tooltip(self::getTitle(...));
        }

        return $action;
    }

    private static function getTitle(
        Action|TableAction $action,
        ViewLog|ListLogs $livewire,
    ): string {
        $model = $action->getRecord() ?? $livewire->record;

        $date = $model?->date ?? $model['date'];

        return __('filament-log-viewer::log.table.actions.download.label', [
            'log' => ParseDateUseCase::execute($date),
        ]);
    }

    private static function getAction(
        Action|TableAction $action,
        ViewLog|ListLogs $livewire,
    ): BinaryFileResponse {
        $model = $action->getRecord() ?? $livewire->record;

        return FilamentLogViewerPlugin::get()->downloadLog($model?->date ?? $model['date']);
    }

    private static function resolveAction(bool $withTooltip): Action|TableAction
    {
        if ($withTooltip) {
            return Action::make('download');
        }

        return TableAction::make('download');
    }
}
