<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Schema\Components;

use Boquizo\FilamentLogViewer\Pages\ViewLog;
use Boquizo\FilamentLogViewer\Utils\Icons;
use Boquizo\FilamentLogViewer\Utils\Level;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Enums\IconSize;
use Illuminate\Database\Eloquent\Builder;

class TabLevel
{
    public static function make(Level|string $level): Tab
    {
        $value = is_string($level) ? $level : $level->value;

        return Tab::make()
            ->label(__("filament-log-viewer::log.levels.{$value}"))
            ->badge(fn (ViewLog $livewire): int => $livewire->record->$value)
            ->icon(Icons::get($value, IconSize::Small))
            ->query(fn (Builder $query) => static::applyLevelFilter($query, $value));
    }

    private static function applyLevelFilter(
        Builder $query,
        string $level
    ): Builder {
        if ($level === Level::ALL) {
            return $query;
        }

        return $query->where('level', $level);
    }
}
