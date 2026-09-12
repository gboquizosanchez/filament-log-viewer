<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\UseCases\ParseDateUseCase;
use Filament\Actions\Action;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class ViewLogModalAction
{
    public static function make(): Action
    {
        return Action::make('view-log')
            ->hiddenLabel()
            ->button()
            ->icon(Heroicon::MagnifyingGlass)
            ->label(__('filament-log-viewer::log.table.actions.view.label'))
            ->color('info')
            ->modalHeading(self::getHeading(...))
            ->modalWidth(Width::SevenExtraLarge)
            ->modalSubmitAction(false)
            ->modalCancelActionLabel(__('filament-log-viewer::log.table.actions.close.label'))
            ->modalContent(self::getModalContent(...));
    }

    private static function getRecordDate(Action $action): string
    {
        $record = $action->getRecord();

        if (is_array($record) && is_string($record['date'] ?? null)) {
            return $record['date'];
        }

        if ($record instanceof Model) {
            $date = $record->getAttribute('date');

            if (is_string($date)) {
                return $date;
            }
        }

        return '';
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
