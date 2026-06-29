<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\FilamentLogViewerServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\Facades\Filament;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Panel;
use Filament\Schemas\SchemasServiceProvider;
use Filament\Support\Icons\Heroicon;
use Filament\Support\Livewire\Partials\DataStoreOverride;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Livewire\LivewireServiceProvider;
use Livewire\Mechanisms\DataStore;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withSession([]);

        // Filament's SupportServiceProvider binds DataStore (non-singleton) which breaks
        // Livewire's state management in tests. Re-register it as a singleton.
        app()->singleton(
            DataStore::class,
            DataStoreOverride::class,
        );

        Filament::registerPanel(
            Panel::make()
                ->id('test')
                ->default()
                ->path('test')
                ->plugin(FilamentLogViewerPlugin::make()),
        );

        Filament::setCurrentPanel(Filament::getPanel('test'));
    }

    protected function getPackageProviders($app): array
    {
        return [
            BladeIconsServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            LivewireServiceProvider::class,
            SupportServiceProvider::class,
            SchemasServiceProvider::class,
            ActionsServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            NotificationsServiceProvider::class,
            TablesServiceProvider::class,
            FilamentServiceProvider::class,
            WidgetsServiceProvider::class,
            FilamentLogViewerServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $fixtures = __DIR__ . '/fixtures/logs';

        $app['config']->set('filament-log-viewer.driver', 'daily');
        $app['config']->set('filament-log-viewer.storage_path', $fixtures);
        $app['config']->set('filament-log-viewer.pattern.prefix', 'laravel-');
        $app['config']->set('filament-log-viewer.pattern.date', '[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]');
        $app['config']->set('filament-log-viewer.pattern.extension', '.log');
        $app['config']->set('filament-log-viewer.clearable', true);
        $app['config']->set('filament-log-viewer.view_in_modal', false);
        $app['config']->set('filament-log-viewer.per-page', [5, 10, 25]);
        $app['config']->set('filament-log-viewer.colors.levels', [
            'all' => '#8A8A8A',
            'emergency' => '#B71C1C',
            'alert' => '#D32F2F',
            'critical' => '#F44336',
            'error' => '#FF5722',
            'warning' => '#FF9100',
            'notice' => '#4CAF50',
            'info' => '#1976D2',
            'debug' => '#90CAF9',
        ]);
        $app['config']->set('filament-log-viewer.icons', [
            'all' => Heroicon::ListBullet,
            'emergency' => Heroicon::BugAnt,
            'alert' => Heroicon::Megaphone,
            'critical' => Heroicon::Fire,
            'error' => Heroicon::XCircle,
            'warning' => Heroicon::ExclamationTriangle,
            'notice' => Heroicon::ExclamationCircle,
            'info' => Heroicon::InformationCircle,
            'debug' => Heroicon::CommandLine,
        ]);
        $app['config']->set('app.timezone', 'UTC');
        $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
        $app['config']->set('session.driver', 'array');
    }
}
