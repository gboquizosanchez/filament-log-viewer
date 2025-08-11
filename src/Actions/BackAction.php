<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Boquizo\FilamentLogViewer\Pages\ListLogs;
use Boquizo\FilamentLogViewer\Pages\ViewLog;
use Filament\Actions\Action;

class BackAction
{
    public static function make(): Action
    {
        return Action::make('back')
            ->hiddenLabel()
            ->tooltip(__('filament-log-viewer::log.table.actions.close.label'))
            ->button()
            ->color('primary')
            ->icon('fas-arrow-left')
            ->action(self::getAction(...));
    }

    private static function getAction(ViewLog $livewire): void
    {
        $livewire->redirect(ListLogs::getUrl());
    }
}
