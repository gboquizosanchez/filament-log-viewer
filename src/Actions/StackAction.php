<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Boquizo\FilamentLogViewer\Infolists\Components\StackTextEntry;
use Boquizo\FilamentLogViewer\Models\Log;
use Filament\Actions\Action;

class StackAction
{
    public static function make(): Action
    {
        return Action::make('stack')
            ->button()
            ->hidden(self::getHidden(...))
            ->icon('fas-toggle-on')
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

    private static function getHidden(Log $record): bool
    {
        return empty($record->stack);
    }
}
