<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Overrides;

use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat as EightyNineStat;
use Illuminate\Contracts\View\View;
use Override;

class Stat extends EightyNineStat
{
    #[Override]
    public function render(): View
    {
        return view('filament-log-viewer::overrides.stat', $this->data());
    }

    public function getProgressBarColor(): string|array|null
    {
        return $this->progressColor;
    }
}
