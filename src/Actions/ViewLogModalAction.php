<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\UseCases\ParseDateUseCase;
use Filament\Actions\Action;
use Illuminate\Contracts\View\View;

class ViewLogModalAction
{
    public static function make(): Action
    {
        return Action::make('viewLog')
            ->hiddenLabel()
            ->button()
            ->icon('heroicon-o-eye')
            ->label(__('filament-log-viewer::log.table.actions.view.label'))
            ->color('info')
            ->modalHeading(fn (Action $action): string => (string) ParseDateUseCase::execute(
                self::getRecordDate($action)
            ))
            ->modalWidth('7xl')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel(__('filament-log-viewer::log.table.actions.close.label'))
            ->modalContent(fn (Action $action): View => view('filament-log-viewer::log-viewer-modal', [
                'log' => FilamentLogViewerPlugin::get()->getLogViewerRecord(self::getRecordDate($action)),
                'timezone' => config('app.timezone'),
            ]));
    }

    private static function getRecordDate(Action $action): string
    {
        $record = $action->getRecord();

        if (is_array($record)) {
            return $record['date'] ?? '';
        }

        return $record?->date ?? '';
    }
}
