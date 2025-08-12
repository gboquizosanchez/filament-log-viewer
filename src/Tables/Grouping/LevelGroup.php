<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Tables\Grouping;

use Boquizo\FilamentLogViewer\Utils\Level;
use Filament\Tables\Grouping\Group;

class LevelGroup
{
    public static function make(): Group
    {
        return Group::make('level')
            ->label(__('filament-log-viewer::log.table.columns.level.label'))
            ->getTitleFromRecordUsing(self::getTitle(...));
    }

    private static function getTitle(array $record): string
    {
        return Level::from($record['level'])->label();
    }
}
