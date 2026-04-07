<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\UseCases\ParseDateUseCase;
use Filament\Actions\Action;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Config;

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
            ->modalHeading(self::getHeading(...))
            ->modalWidth('7xl')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel(__('filament-log-viewer::log.table.actions.close.label'))
            ->modalContent(self::getModalContent(...));
    }

    private static function getRecordDate(Action $action): string
    {
        $record = $action->getRecord();

        if (is_array($record)) {
            return $record['date'] ?? '';
        }

        return $record?->date ?? '';
    }

    private static function getHeading(Action $action): string
    {
        return ParseDateUseCase::execute(
            self::getRecordDate($action)
        );
    }

    private static function getModalContent(Action $action): View
    {
        $record = FilamentLogViewerPlugin::get()
            ->getLogViewerRecord(self::getRecordDate($action));

        return view('filament-log-viewer::log-viewer-modal', [
            'log' => $record,
            'timezone' => Config::string('app.timezone'),
        ]);
    }
}
