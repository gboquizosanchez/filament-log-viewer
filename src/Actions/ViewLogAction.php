<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Boquizo\FilamentLogViewer\Pages\ViewLog;
use Filament\Actions\ViewAction;

class ViewLogAction
{
    public static function make(): ViewAction
    {
        return ViewAction::make()
            ->hiddenLabel()
            ->button()
            ->icon('fas-search')
            ->url(self::getUrl(...))
            ->label(__('filament-log-viewer::log.table.actions.view.label'))
            ->color('info');
    }

    private static function getUrl(array $record): string
    {
        return ViewLog::getUrl([
            'record' => $record['date'],
        ]);
    }
}
