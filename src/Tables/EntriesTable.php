<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Tables;

use Boquizo\FilamentLogViewer\Actions\ContextAction;
use Boquizo\FilamentLogViewer\Actions\StackAction;
use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Pages\ViewLog;
use Boquizo\FilamentLogViewer\Tables\Columns\ContextColumn;
use Boquizo\FilamentLogViewer\Tables\Columns\DateColumn;
use Boquizo\FilamentLogViewer\Tables\Columns\EnvColumn;
use Boquizo\FilamentLogViewer\Tables\Columns\MessageColumn;
use Boquizo\FilamentLogViewer\Tables\Columns\LevelColumn;
use Boquizo\FilamentLogViewer\Tables\Columns\StackColumn;
use Boquizo\FilamentLogViewer\Utils\Level;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EntriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->records(self::getRecords(...))
            ->header(self::getHeader(...))
            // TODO: Fix grouping. Groups not working with custom data feature.
            // ->groups([
            //     LevelGroup::make(),
            // ])
            ->paginationPageOptions(
                Config::array('filament-log-viewer.per-page'),
            )
            ->columns([
                EnvColumn::make(),
                DateColumn::make('datetime'),
                LevelColumn::make(),
                MessageColumn::make(),
                StackColumn::make(),
                ContextColumn::make(),
            ])
            ->recordActions([
                StackAction::make(),
                ContextAction::make(),
            ])
            ->filters([
                SelectFilter::make('level')
                    ->label(__('filament-log-viewer::log.table.columns.level.label'))
                    ->options(Level::options(withoutAll: true)),
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

    private static function getRecords(
        ViewLog $livewire,
        array $filters,
        ?string $sortColumn,
        ?string $sortDirection,
        ?string $search,
        int $page,
        int $recordsPerPage,
    ): LengthAwarePaginator {
        $records = FilamentLogViewerPlugin::get()
            ->getLogViewerRecord($livewire->record->date)
            ->toModel();

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
                        Str::lower($record['datetime'] ?? ''),
                        Str::lower($search),
                    ) || Str::contains(
                        Str::lower($record['header'] ?? ''),
                        Str::lower($search),
                    ),
                ),
            )
            ->when(
                filled($level = $filters['level']['value'] ?? null),
                fn (Collection $data): Collection => $data->where(
                    'level', $level
                ),
            );

        $total = $collection->count();

        $data = $collection->forPage($page, $recordsPerPage);

        return new LengthAwarePaginator(
            $data,
            total: $total,
            perPage: $recordsPerPage,
            currentPage: $page,
        );
    }
}
