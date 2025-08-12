<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Schema\Components;

use Boquizo\FilamentLogViewer\Pages\ViewLog;
use Boquizo\FilamentLogViewer\Utils\Icons;
use Boquizo\FilamentLogViewer\Utils\Level;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Enums\IconSize;

class TabLevel
{
    public static function make(Level|string $level): Tab
    {
        $value = is_string($level) ? $level : $level->value;

        return Tab::make()
            ->label(__("filament-log-viewer::log.levels.{$value}"))
            ->badge(fn (ViewLog $livewire): int => $livewire->record->$value)
            ->icon(Icons::get($value, IconSize::Small))
            ->extraAttributes([
                'wire:click' => self::buildWireClickActions($value),
            ]);
    }
    private static function buildWireClickActions(string $value): string
    {
        $setActiveTab = "\$set('activeTab', '{$value}')";
        $filterValue = $value === Level::ALL ? 'null' : "'{$value}'";
        $setFilterLevel = "\$set('tableFilters.level.value', {$filterValue})";


        return "{$setActiveTab}; {$setFilterLevel};";
    }
}
