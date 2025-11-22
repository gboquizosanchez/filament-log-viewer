<?php

namespace Boquizo\FilamentLogViewer;

use Boquizo\FilamentLogViewer\Widgets\IconsWidget;
use Boquizo\FilamentLogViewer\Widgets\StatsOverviewWidget;
use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Str;
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

        if (version_compare(Application::VERSION, '11.0.0', '<')) {
            $this->polyfills();
        }
    }

    public function packageBooted(): void
    {
        Livewire::component('stats-overview-widget', StatsOverviewWidget::class);
        Livewire::component('icons-widget', IconsWidget::class);
    }

    public function polyfills(): void
    {
        Repository::macro('string', function (string $key, mixed $default = null): string {
            $value = $this->get($key, $default);

            return (string) Str::of($value);
        });

        Repository::macro('array', function (string $key, array $default = []): array {
            $value = $this->get($key, $default);

            return is_array($value) ? $value : (array) $value;
        });

        Repository::macro('boolean', function (string $key, bool $default = false): bool {
            $value = $this->get($key, $default);

            return is_bool($value) ? $value : (bool) $value;
        });
    }
}
