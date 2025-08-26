<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Pages\ListLogs;
use Boquizo\FilamentLogViewer\Pages\ViewLog;
use Boquizo\FilamentLogViewer\UseCases\ParseDateUseCase;
use Exception;
use Filament\Actions\DeleteAction as FilamentDeleteAction;

class DeleteAction
{
    public static function make(
        bool $withTooltip = false,
    ): FilamentDeleteAction {
        $action = FilamentDeleteAction::make()
            ->hiddenLabel()
            ->button()
            ->hidden(false)
            ->label(__('filament-log-viewer::log.table.actions.delete.label'))
            ->modalHeading(self::getTitle(...))
            ->color('danger')
            ->successNotificationTitle(
                __('filament-log-viewer::log.table.actions.delete.success'),
            )
            ->failureNotificationTitle(
                __('filament-log-viewer::log.table.actions.delete.error'),
            )
            ->icon('fas-trash')
            ->requiresConfirmation()
            ->action(self::getAction(...))
            // I have to set this manually because the default is not working
            ->successRedirectUrl(ListLogs::getUrl());

        if ($withTooltip) {
            $action->tooltip(self::getTitle(...))
                ->hidden(false);
        }

        return $action;
    }

    private static function getTitle(
        FilamentDeleteAction $action,
        ViewLog|ListLogs $livewire,
    ): string {
        $model = $action->getRecord() ?? $livewire->record;

        $date = $model?->date ?? $model['date'];

        return __('filament-log-viewer::log.table.actions.delete.label', [
            'log' => ParseDateUseCase::execute($date),
        ]);
    }

    private static function getAction(
        FilamentDeleteAction $action,
        ViewLog|ListLogs $livewire,
    ): void {
        try {
            $model = $action->getRecord() ?? $livewire->record;

            FilamentLogViewerPlugin::get()->deleteLog($model?->date ?? $model['date']);
        } catch (Exception) {
            $action->failure();
        }
    }
}
