<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Schema\Components;

use Boquizo\FilamentLogViewer\Utils\Icons;
use Boquizo\FilamentLogViewer\Utils\Level;
use Filament\Resources\Components\Tab;
use Filament\Support\Enums\IconSize;

class TabLevel
{
    public static function make(Level|string $level): Tab
    {
        $value = is_string($level) ? $level : $level->value;

        return Tab::make()
            ->label(__("filament-log-viewer::log.levels.{$value}"))
            ->icon(Icons::get($value, IconSize::Small));
    }
}
