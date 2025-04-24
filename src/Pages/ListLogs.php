<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Pages;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Models\LogStat;
use Boquizo\FilamentLogViewer\Utils\Icons;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ListLogs extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $view = 'filament-log-viewer::list-logs';

    public static function table(Table $table): Table
    {
        return $table
            ->query(LogStat::query())
            ->paginationPageOptions(
                Config::array('filament-log-viewer.per-page'),
            )
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label(__('filament-log-viewer::log.table.columns.date.label'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('all')
                    ->label(Icons::get('all', IconSize::Small))
                    ->sortable(),
                Tables\Columns\TextColumn::make('emergency')
                    ->label(Icons::get('emergency', IconSize::Small))
                    ->sortable(),
                Tables\Columns\TextColumn::make('alert')
                    ->label(Icons::get('alert', IconSize::Small))
                    ->sortable(),
                Tables\Columns\TextColumn::make('critical')
                    ->label(Icons::get('critical', IconSize::Small))
                    ->sortable(),
                Tables\Columns\TextColumn::make('error')
                    ->label(Icons::get('error', IconSize::Small))
                    ->sortable(),
                Tables\Columns\TextColumn::make('warning')
                    ->label(Icons::get('warning', IconSize::Small))
                    ->sortable(),
                Tables\Columns\TextColumn::make('notice')
                    ->label(Icons::get('notice', IconSize::Small))
                    ->sortable(),
                Tables\Columns\TextColumn::make('info')
                    ->label(Icons::get('info', IconSize::Small))
                    ->sortable(),
                Tables\Columns\TextColumn::make('debug')
                    ->label(Icons::get('debug', IconSize::Small))
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->hiddenLabel()
                    ->button()
                    ->icon('fas-search')
                    ->url(fn (LogStat $record): string => ViewLog::getUrl([
                        'record' => $record->date,
                    ]))
                    ->label(__('filament-log-viewer::log.table.actions.view.label'))
                    ->color('info'),
                Tables\Actions\Action::make('download')
                    ->hiddenLabel()
                    ->button()
                    ->label(__('filament-log-viewer::log.table.actions.download.label'))
                    ->color('success')
                    ->icon('fas-download')
                    ->requiresConfirmation()
                    ->action(
                        fn (LogStat $record): BinaryFileResponse => FilamentLogViewerPlugin::get()
                            ->downloadLog($record->date)
                    ),
                Tables\Actions\DeleteAction::make()
                    ->hiddenLabel()
                    ->button()
                    ->label(__('filament-log-viewer::log.table.actions.delete.label'))
                    ->color('danger')
                    ->icon('fas-trash')
                    ->requiresConfirmation()
                    ->action(function (LogStat $record): void {
                        FilamentLogViewerPlugin::get()->deleteLog($record->date)
                            ? Notification::make()
                                ->title(__('filament-log-viewer::log.table.actions.delete.success'))
                                ->success()
                                ->send()
                            : Notification::make()
                                ->title(__('filament-log-viewer::log.table.actions.delete.error'))
                                ->danger()
                                ->send();
                    }),
            ]);
    }

    public static function getNavigationGroup(): ?string
    {
        return FilamentLogViewerPlugin::get()->getNavigationGroup();
    }

    public static function getNavigationSort(): ?int
    {
        return FilamentLogViewerPlugin::get()->getNavigationSort();
    }

    public static function getNavigationIcon(): string
    {
        return FilamentLogViewerPlugin::get()->getNavigationIcon();
    }

    public static function getNavigationLabel(): string
    {
        return FilamentLogViewerPlugin::get()->getNavigationLabel();
    }

    public static function getSlug(): string
    {
        return FilamentLogViewerPlugin::get()->getSlug();
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
