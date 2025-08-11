<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Pages;

use BackedEnum;
use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Tables\LogsTable;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Config;

class ListLogs extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament-log-viewer::list-logs';

    public static function table(Table $table): Table
    {
        return LogsTable::configure($table);
    }

    public static function getNavigationGroup(): ?string
    {
        return FilamentLogViewerPlugin::get()->getNavigationGroup();
    }

    public static function getNavigationSort(): ?int
    {
        return FilamentLogViewerPlugin::get()->getNavigationSort();
    }

    public static function getNavigationIcon(): string|BackedEnum|Htmlable|null
    {
        return FilamentLogViewerPlugin::get()->getNavigationIcon();
    }

    public static function getNavigationLabel(): string
    {
        return FilamentLogViewerPlugin::get()->getNavigationLabel();
    }

    public static function getCluster(): ?string
    {
        return Config::get('filament-log-viewer.resource.cluster');
    }

    public static function getSlug(?Panel $panel = null): string
    {
        return Config::string('filament-log-viewer.resource.slug', 'logs');
    }

    public static function canAccess(): bool
    {
        return FilamentLogViewerPlugin::get()->isAuthorized();
    }

    public function getTitle(): string
    {
        return __('filament-log-viewer::log.dashboard.title');
    }
}
