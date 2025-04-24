<?php

namespace Boquizo\FilamentLogViewer;

use Boquizo\FilamentLogViewer\Widgets\IconsWidget;
use Boquizo\FilamentLogViewer\Widgets\StatsOverviewWidget;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentLogViewerServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-log-viewer';

    public static string $viewNamespace = 'filament-log-viewer';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command->publishConfigFile()
                    ->askToStarRepoOnGitHub('gboquizosanchez/filament-log-viewer');
            });

        if (file_exists($package->basePath('/../config/' . static::$name . '.php'))) {
            $package->hasConfigFile(static::$name);
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageBooted(): void
    {
        Livewire::component('stats-overview-widget', StatsOverviewWidget::class);
        Livewire::component('icons-widget', IconsWidget::class);
    }
}
