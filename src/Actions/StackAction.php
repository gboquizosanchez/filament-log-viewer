<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Boquizo\FilamentLogViewer\Infolists\Components\StackTextEntry;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;

class StackAction
{
    public static function make(): Action
    {
        return Action::make('stack')
            ->button()
            ->hidden(self::getHidden(...))
            ->icon(Heroicon::InformationCircle)
            ->color('gray')
            ->schema([
                StackTextEntry::make(),
            ])
            ->modalHeading('')
            ->modalWidth('7xl')
            ->modalCancelActionLabel(
                __('filament-log-viewer::log.table.actions.close.label'),
            )
            ->modalSubmitAction(false);
    }

    private static function getHidden(array | object $record): bool
    {
        return empty($record->stack ?? $record['stack']);
    }
}
