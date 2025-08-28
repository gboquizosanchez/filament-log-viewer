<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Tables;

use Boquizo\FilamentLogViewer\Actions\ContextAction;
use Boquizo\FilamentLogViewer\Actions\StackAction;
use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Models\Log;
use Boquizo\FilamentLogViewer\Pages\ViewLog;
use Boquizo\FilamentLogViewer\Tables\Columns\ContextColumn;
use Boquizo\FilamentLogViewer\Tables\Columns\EnvColumn;
use Boquizo\FilamentLogViewer\Tables\Columns\LevelColumn;
use Boquizo\FilamentLogViewer\Tables\Columns\MessageColumn;
use Boquizo\FilamentLogViewer\Tables\Columns\NameColumn;
use Boquizo\FilamentLogViewer\Tables\Columns\StackColumn;
use Boquizo\FilamentLogViewer\Tables\Groups\LevelGroup;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class EntriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(Log::query())
            ->header(
                fn (ViewLog $livewire) => view('filament-log-viewer::log-information', [
                    'data' => FilamentLogViewerPlugin::get()->getLogViewerRecord(),
                ]),
            )
            // Groups working properly in v1
            ->groups([
                LevelGroup::make(),
            ])
            ->paginationPageOptions(
                Config::array('filament-log-viewer.per-page'),
            )
            ->columns([
                EnvColumn::make(),
                NameColumn::make('datetime'),
                LevelColumn::make(),
                MessageColumn::make(),
                StackColumn::make(),
                ContextColumn::make(),
            ])
            ->actions([
                StackAction::make(),
                ContextAction::make(),
            ]);
    }

    private static function getHeader(ViewLog $livewire): View
    {
        return view('filament-log-viewer::log-information', [
            'data' => FilamentLogViewerPlugin::get()
                ->getLogViewerRecord(
                    $livewire->record->date,
                ),
        ]);
    }
}
