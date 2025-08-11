<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Tables;

use Boquizo\FilamentLogViewer\Actions\ContextAction;
use Boquizo\FilamentLogViewer\Actions\StackAction;
use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Models\Log;
use Boquizo\FilamentLogViewer\Tables\Columns\ContextColumn;
use Boquizo\FilamentLogViewer\Tables\Columns\DateColumn;
use Boquizo\FilamentLogViewer\Tables\Columns\EnvColumn;
use Boquizo\FilamentLogViewer\Tables\Columns\HeaderColumn;
use Boquizo\FilamentLogViewer\Tables\Columns\LevelColumn;
use Boquizo\FilamentLogViewer\Tables\Columns\StackColumn;
use Boquizo\FilamentLogViewer\Tables\Grouping\LevelGroup;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class LogTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(Log::query())
            ->header(self::getHeader(...))
            ->groups([
                LevelGroup::make(),
            ])
            ->paginationPageOptions(
                Config::array('filament-log-viewer.per-page'),
            )
            ->columns([
                EnvColumn::make(),
                DateColumn::make('datetime'),
                LevelColumn::make(),
                HeaderColumn::make(),
                StackColumn::make(),
                ContextColumn::make(),
            ])
            ->recordActions([
                StackAction::make(),
                ContextAction::make(),
            ]);
    }

    private static function getHeader(): View
    {
        return view('filament-log-viewer::log-information', [
            'data' => FilamentLogViewerPlugin::get()->getLogViewerRecord(),
        ]);
    }
}
