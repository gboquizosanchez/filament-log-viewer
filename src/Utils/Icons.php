<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Utils;

use Filament\Support\Enums\IconSize;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\HtmlString;

class Icons
{
    public static function get(string $name, IconSize $iconSize): HtmlString
    {
        $colors = Config::array('filament-log-viewer.colors.levels');
        $icons = Config::array('filament-log-viewer.icons');

        return new HtmlString(
            Blade::render(
                sprintf('
                    <x-%s class="%s" style="color: %s"/>',
                    $icons[$name],
                    self::size($iconSize),
                    $colors[$name],
                ),
            ),
        );
    }

    private static function size(IconSize $size): string
    {
        return match ($size) {
            IconSize::Small => 'w-4 h-4',
            IconSize::Medium => 'w-5 h-5',
            IconSize::Large => 'w-8 h-8',
        };
    }
}
