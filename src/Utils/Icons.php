<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Utils;

use Filament\Support\Contracts\ScalableIcon;
use Filament\Support\Enums\IconSize;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\HtmlString;
use TypeError;

class Icons
{
    public static function get(string $name, IconSize $size): HtmlString
    {
        $colors = Config::array('filament-log-viewer.colors.levels');
        $icons = Config::array('filament-log-viewer.icons');

        $icon = $icons[$name] ?? null;

        if ($icon instanceof ScalableIcon) {
            $icon = $icon->getIconForSize($size);
        }

        if (! is_string($icon)) {
            throw new TypeError(
                "The configured icon for [{$name}] must be a string or scalable icon.",
            );
        }

        $color = $colors[$name] ?? '#8A8A8A';

        if (! is_string($color)) {
            $color = '#8A8A8A';
        }

        return new HtmlString(
            Blade::render(
                sprintf(
                    '<x-%s class="%s" style="color: %s"/>',
                    $icon,
                    self::size($size),
                    $color,
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
