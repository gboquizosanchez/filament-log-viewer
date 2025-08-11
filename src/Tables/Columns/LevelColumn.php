<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Tables\Columns;

use Boquizo\FilamentLogViewer\Utils\Icons;
use Boquizo\FilamentLogViewer\Utils\Level;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;

class LevelColumn
{
    private static IconSize $size;

    public static function make(Level|string|null $level = null): TextColumn
    {
        $column = $level === null
            ? self::iconizedColumn()
            : self::leveledColumn($level);

        return $column->sortable();
    }

    private static function iconizedColumn(): TextColumn
    {
        self::$size = IconSize::Medium;

        return TextColumn::make('level')
            ->alignCenter()
            ->tooltip(self::getTooltip(...))
            ->label(__('filament-log-viewer::log.table.columns.level.label'))
            ->formatStateUsing(self::getFormatStateUsing(...));
    }

    private static function leveledColumn(Level|string $level): TextColumn
    {
        self::$size = IconSize::Small;

        $value = is_string($level) ? $level : $level->value;

        return TextColumn::make($value)
            ->label(self::getFormatStateUsing($value));
    }

    private static function getTooltip(string $state): string
    {
        return Level::from($state)->label();
    }

    private static function getFormatStateUsing(string $state): HtmlString
    {
        return Icons::get(name: $state, size: self::$size);
    }
}
