<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Widgets;

use Filament\Widgets\StatsOverviewWidget\Stat as FilamentStat;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\HtmlString;

use const Boquizo\FilamentLogViewer\Utils\LEVEL_ALL;

class Stat
{
    public static function make(string $level, array $data): FilamentStat
    {
        $label = $data['name'];
        $value = $data['count'];
        $progressColor = self::getProgressColor($level, $data);

        return FilamentStat::make($label, $value)
            ->label(self::getLabel($label, $progressColor))
            ->icon(Config::string("filament-log-viewer.icons.{$level}"))
            ->description(self::getDescription([
                'progressColor' => $progressColor,
                'style' => self::getStyle($level, $progressColor),
                'percent' => $data['percent'],
            ]));
    }

    private static function getProgressColor(string $level, array $data): string
    {
        return Arr::get($data, "totals.{$level}.color", '#8A8A8A');
    }

    private static function getLabel(string $name, string $progressColor): HtmlString
    {
        return new HtmlString(
            "<div class='dark:text-white' style='color: {$progressColor}'>
                {$name}
            </div>"
        );
    }

    private static function getStyle(string $level, string $progressColor): string
    {
        $style = '<style>';

        if ($level === LEVEL_ALL) {
            $style .= self::getResetProgressBarStyle();
        }

        $index = self::getIndex($level);

        $style .= <<<CSS
.fi-sc-has-gap > div:nth-child({$index}) .fi-wi-stats-overview-stat-label-ctn > .fi-icon {
    color: white !important;
    background-color: {$progressColor};
    border-radius: 5px;
    padding: 3px;
    width: calc(var(--spacing) * 9);
    height: calc(var(--spacing) * 9);
}
CSS;

        $style .= '</style>';

        return $style;
    }

    private static function getResetProgressBarStyle(): string
    {
        return <<<CSS
.fi-wi-stats-overview-stat-description {
    display: inherit !important;
}
CSS;
    }

    private static function getIndex(string $level): int
    {
        $colors = Config::array('filament-log-viewer.colors.levels');

        return array_search($level, array_keys($colors), true) + 1;
    }

    private static function getDescription(array $array): HtmlString
    {
        return new HtmlString(
            view('filament-log-viewer::progress-bar', $array),
        );
    }
}
