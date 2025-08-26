<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Tables;

use Boquizo\FilamentLogViewer\Actions\ClearLogAction;
use Boquizo\FilamentLogViewer\Actions\DeleteAction;
use Boquizo\FilamentLogViewer\Actions\DeleteBulkAction;
use Boquizo\FilamentLogViewer\Actions\DownloadAction;
use Boquizo\FilamentLogViewer\Actions\DownloadBulkAction;
use Boquizo\FilamentLogViewer\Actions\ViewLogAction;
use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Tables\Columns\NameColumn;
use Boquizo\FilamentLogViewer\Tables\Columns\LevelColumn;
use Boquizo\FilamentLogViewer\Utils\Level;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class LogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->records(self::getRecords(...))
            ->resolveSelectedRecordsUsing(
                self::getResolveSelectedRecordsUsing(...),
            )
            ->paginationPageOptions(
                Config::array('filament-log-viewer.per-page'),
            )
            ->columns([
                NameColumn::make('date'),
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
                ClearLogAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DownloadBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    private static function getRecords(
        ?string $sortColumn,
        ?string $sortDirection,
        ?string $search,
        int $page,
        int $recordsPerPage,
    ): LengthAwarePaginator {
        $records = FilamentLogViewerPlugin::get()->getLogsTableRecords();

        $collection = collect($records)
            ->when(
                filled($sortColumn),
                fn (Collection $collection) => $collection->sortBy(
                    $sortColumn,
                    SORT_REGULAR,
                    $sortDirection === 'desc',
                ),
            )
            ->when(
                filled($search),
                fn (Collection $collection) => $collection->filter(
                    fn (array $record) => Str::contains(
                        Str::lower($record['date']),
                        Str::lower($search),
                    ),
                ),
            );

        $total = $collection->count();

        $data = $collection->forPage($page, $recordsPerPage);

        $isEmpty = self::isEmpty($data);

        return new LengthAwarePaginator(
            $isEmpty ? [] : $data,
            total: $isEmpty ? 0 : $total,
            perPage: $recordsPerPage,
            currentPage: $page,
        );
    }

    private static function getResolveSelectedRecordsUsing(
        array $keys,
        bool $isTrackingDeselectedKeys,
        array $deselectedKeys,
    ): Collection {
        $records = collect(FilamentLogViewerPlugin::get()->getLogsTableRecords());

        if ($isTrackingDeselectedKeys) {
            return $records->except($deselectedKeys)->values();
        }

        return $records->only($keys)->values();
    }

    private static function isEmpty(Collection $data): bool
    {
        $firstRecord = collect($data->first());

        return $data->count() === 1 && $firstRecord->filter()->count() === 1;
    }
}
