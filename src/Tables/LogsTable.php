<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Tables;

use Boquizo\FilamentLogViewer\Actions\DeleteAction;
use Boquizo\FilamentLogViewer\Actions\DeleteBulkAction;
use Boquizo\FilamentLogViewer\Actions\DownloadAction;
use Boquizo\FilamentLogViewer\Actions\DownloadBulkAction;
use Boquizo\FilamentLogViewer\Actions\ViewLogAction;
use Boquizo\FilamentLogViewer\Models\LogStat;
use Boquizo\FilamentLogViewer\Tables\Columns\DateColumn;
use Boquizo\FilamentLogViewer\Tables\Columns\LevelColumn;
use Boquizo\FilamentLogViewer\Utils\Level;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Config;

class LogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(LogStat::query())
            ->paginationPageOptions(
                Config::array('filament-log-viewer.per-page'),
            )
            ->columns([
                DateColumn::make('date'),
                LevelColumn::make(Level::ALL),
                LevelColumn::make(Level::Emergency),
                LevelColumn::make(Level::Alert),
                LevelColumn::make(Level::Critical),
                LevelColumn::make(Level::Error),
                LevelColumn::make(Level::Warning),
                LevelColumn::make(Level::Notice),
                LevelColumn::make(Level::Info),
                LevelColumn::make(Level::Debug),
            ])
            ->recordActions([
                ViewLogAction::make(),
                DownloadAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DownloadBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
        ]);
    }
}
