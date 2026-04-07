<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Boquizo\FilamentLogViewer\Infolists\Components\ContextTextEntry;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;

class ContextAction
{
    public static function make(): Action
    {
        return Action::make('context')
            ->button()
            ->hidden(self::getHidden(...))
            ->icon(Heroicon::InformationCircle)
            ->color('gray')
            ->schema([
                ContextTextEntry::make(),
            ])
            ->modalHeading('')
            ->modalWidth('7xl')
            ->modalCancelActionLabel(
                __('filament-log-viewer::log.table.actions.close.label'),
            )
            ->modalSubmitAction(false);
    }

    private static function getHidden(array $record): bool
    {
        return $record['context'] === '[]';
    }
}
