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
            IconSize::ExtraSmall => 'fi-icon fi-size-xs',
            IconSize::Small => 'fi-icon fi-size-sm',
            IconSize::Medium => 'fi-icon',
            IconSize::Large => 'fi-icon fi-size-lg',
            IconSize::ExtraLarge => 'fi-icon fi-size-xl',
            IconSize::TwoExtraLarge => 'fi-icon fi-size-2xl',
        };
    }
}
