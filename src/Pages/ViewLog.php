<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Pages;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Models\Log;
use Boquizo\FilamentLogViewer\Models\LogStat;
use Boquizo\FilamentLogViewer\Utils\Icons;
use Boquizo\FilamentLogViewer\Utils\Level;
use Filament\Actions;
use Filament\Actions\DeleteAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Resources\Components\Tab;
use Filament\Resources\Concerns\HasTabs;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Locked;
use Override;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ViewLog extends Page implements HasTable
{
    use HasTabs;
    use InteractsWithTable;

    #[Locked]
    public LogStat|string|null $record;

    protected static string $view = 'filament-log-viewer::view-log';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static bool $shouldRegisterNavigation = false;

    public static function table(Table $table): Table
    {
        return $table
            ->query(Log::query())
            ->header(
                fn (ViewLog $livewire) => view('filament-log-viewer::log-information', [
                    'data' => FilamentLogViewerPlugin::get()->getLogViewerRecord(),
                ]),
            )
            ->groups([
                Group::make('level')
                    ->label(__('filament-log-viewer::log.table.columns.level.label'))
                    ->getTitleFromRecordUsing(
                        fn (Log $record): string => Level::from($record->level)->label()
                    ),
            ])
            ->paginationPageOptions(
                Config::array('filament-log-viewer.per-page'),
            )
            ->columns([
                Tables\Columns\TextColumn::make('env')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'production' => 'danger',
                        'staging' => 'orange',
                        default => 'success',
                    })
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('datetime')
                    ->label(__('filament-log-viewer::log.table.columns.date.label'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('level')
                    ->alignCenter()
                    ->tooltip(fn (string $state): string => Level::from($state)->label())
                    ->label(__('filament-log-viewer::log.table.columns.level.label'))
                    ->formatStateUsing(
                        fn (string $state): HtmlString => Icons::get($state, IconSize::Medium),
                    )
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('header')
                    ->label(__('filament-log-viewer::log.table.columns.message.label'))
                    ->wrap()
                    ->searchable()
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stack')
                    ->searchable()
                    ->label('')
                    ->extraAttributes([
                        'class' => 'hidden',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('stack')
                    ->button()
                    ->hidden(fn (Log $record): bool => empty($record->stack) || empty($record->context))
                    ->icon('fas-toggle-on')
                    ->color('gray')
                    ->infolist([
                        TextEntry::make('stack')
                            ->hiddenLabel()
                            ->fontFamily(FontFamily::Mono)
                            ->html()
                            ->extraAttributes([
                                'class' => 'overflow-auto',
                                'style' => 'max-height: 35rem;',
                            ])
                            ->hidden(fn (Log $record): bool => empty($record->stack))
                            ->formatStateUsing(
                                fn (Log $record): string => preg_replace(
                                    '/(.*vendor.*$)/m',
                                    '<span class="text-gray-400">$1</span>',
                                    nl2br($record->stack),
                                ),
                            ),
                    ])
                    ->modalHeading('')
                    ->modalWidth(MaxWidth::ScreenExtraLarge)
                    ->modalCancelActionLabel(__('filament-log-viewer::log.table.actions.close.label'))
                    ->modalSubmitAction(false),
            ]);
    }

    #[Override]
    public function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download')
                ->hiddenLabel()
                ->tooltip(__('filament-log-viewer::log.table.actions.download.label', [
                    'log' => Carbon::parse($this->record->date)->isoFormat('LL'),
                ]))
                ->button()
                ->modalHeading(__('filament-log-viewer::log.table.actions.download.label', [
                    'log' => Carbon::parse($this->record->date)->isoFormat('LL'),
                ]))
                ->label(__('filament-log-viewer::log.table.actions.download.label'))
                ->color('success')
                ->icon('fas-download')
                ->requiresConfirmation()
                ->action(
                    fn (): BinaryFileResponse => FilamentLogViewerPlugin::get()
                        ->downloadLog($this->record->date)
                ),
            DeleteAction::make()
                ->hiddenLabel()
                ->tooltip(__('filament-log-viewer::log.table.actions.delete.label', [
                    'log' => Carbon::parse($this->record->date)->isoFormat('LL'),
                ]))
                ->hidden(false)
                ->button()
                ->modalHeading(__('filament-log-viewer::log.table.actions.delete.label', [
                    'log' => Carbon::parse($this->record->date)->isoFormat('LL'),
                ]))
                ->label(__('filament-log-viewer::log.table.actions.delete.label'))
                ->color('danger')
                ->icon('fas-trash')
                ->requiresConfirmation()
                ->action(function (): void {
                    FilamentLogViewerPlugin::get()->deleteLog($this->record->date)
                        ? Notification::make()
                            ->title(__('filament-log-viewer::log.table.actions.delete.success'))
                            ->success()
                            ->send()
                        : Notification::make()
                            ->title(__('filament-log-viewer::log.table.actions.delete.error'))
                            ->danger()
                            ->send();

                    $this->redirect(ListLogs::getUrl());
                }),
            Actions\Action::make('back')
                ->hiddenLabel()
                ->tooltip(__('filament-log-viewer::log.table.actions.close.label'))
                ->button()
                ->color('primary')
                ->icon('fas-arrow-left')
                ->action(
                    fn () => $this->redirect(ListLogs::getUrl())
                ),
        ];
    }

    protected function makeTable(): Table
    {
        return Table::make($this)
            ->modifyQueryUsing($this->modifyQueryWithActiveTab(...));
    }

    public static function canAccess(): bool
    {
        return FilamentLogViewerPlugin::get()->isAuthorized();
    }

    /**
     * Changed to ->make() instead of ->get() until filamentphp/filament fix the issue.
     *
     * @see https://github.com/filamentphp/filament/issues/16037
     * @see https://github.com/gboquizosanchez/filament-log-viewer/issues/1
     */
    public static function getSlug(): string
    {
        return FilamentLogViewerPlugin::make()->getSlug() . '/{record}';
    }

    public function mount(string $record): void
    {
        $this->record = LogStat::query()->where('date', $record)->firstOrFail();

        Session::put('filament-log-viewer-record', $this->record->date);

        $this->loadDefaultActiveTab();
    }

    /** @return array<string, Tab> */
    public function getTabs(): array
    {
        if (\in_array(
            $this->record->all,
            Arr::except($this->record->toArray(), ['all']),
            true)
        ) {
            return [];
        }

        return [
            'all' => Tab::make()
                ->label(__('All'))
                ->badge($this->record->all)
                ->when($this->record->all === 0, function (Tab $tab): void {
                    $tab->extraAttributes([
                        'class' => 'hidden',
                    ]);
                })
                ->icon(Icons::get('all', IconSize::Small)),
            'emergency' => Tab::make()
                ->label(__('Emergency'))
                ->badge($this->record->emergency)
                ->icon(Icons::get('emergency', IconSize::Small))
                ->when($this->record->emergency === 0, function (Tab $tab): void {
                    $tab->extraAttributes([
                        'class' => 'hidden',
                    ]);
                })
                ->query(function (Builder $query) {
                    return $query->where('level', 'emergency');
                }),
            'alert' => Tab::make()
                ->label(__('Alert'))
                ->badge($this->record->alert)
                ->icon(Icons::get('alert', IconSize::Small))
                ->when($this->record->alert === 0, function (Tab $tab): void {
                    $tab->extraAttributes([
                        'class' => 'hidden',
                    ]);
                })
                ->query(function (Builder $query) {
                    return $query->where('level', 'alert');
                }),
            'critical' => Tab::make()
                ->label(__('Critical'))
                ->badge($this->record->critical)
                ->icon(Icons::get('critical', IconSize::Small))
                ->when($this->record->critical === 0, function (Tab $tab): void {
                    $tab->extraAttributes([
                        'class' => 'hidden',
                    ]);
                })
                ->query(function (Builder $query) {
                    return $query->where('level', 'critical');
                }),
            'error' => Tab::make()
                ->label(__('Error'))
                ->badge($this->record->error)
                ->icon(Icons::get('error', IconSize::Small))
                ->when($this->record->error === 0, function (Tab $tab): void {
                    $tab->extraAttributes([
                        'class' => 'hidden',
                    ]);
                })
                ->query(function (Builder $query) {
                    return $query->where('level', 'error');
                }),
            'warning' => Tab::make()
                ->label(__('Warning'))
                ->badge($this->record->warning)
                ->icon(Icons::get('warning', IconSize::Small))
                ->when($this->record->warning === 0, function (Tab $tab): void {
                    $tab->extraAttributes([
                        'class' => 'hidden',
                    ]);
                })
                ->query(function (Builder $query) {
                    return $query->where('level', 'warning');
                }),
            'notice' => Tab::make()
                ->label(__('Notice'))
                ->badge($this->record->notice)
                ->icon(Icons::get('notice', IconSize::Small))
                ->when($this->record->notice === 0, function (Tab $tab): void {
                    $tab->extraAttributes([
                        'class' => 'hidden',
                    ]);
                })
                ->query(function (Builder $query) {
                    return $query->where('level', 'notice');
                }),
            'info' => Tab::make()
                ->label(__('Info'))
                ->badge($this->record->info)
                ->icon(Icons::get('info', IconSize::Small))
                ->when($this->record->info === 0, function (Tab $tab): void {
                    $tab->extraAttributes([
                        'class' => 'hidden',
                    ]);
                })
                ->query(function (Builder $query) {
                    return $query->where('level', 'info');
                }),
            'debug' => Tab::make()
                ->label(__('Debug'))
                ->badge($this->record->debug)
                ->icon(Icons::get('debug', IconSize::Small))
                ->when($this->record->debug === 0, function (Tab $tab): void {
                    $tab->extraAttributes([
                        'class' => 'hidden',
                    ]);
                })
                ->query(function (Builder $query) {
                    return $query->where('level', 'debug');
                }),
        ];
    }

    public function getDefaultActiveTab(): string|int|null
    {
        return 'all';
    }

    public function getTitle(): string
    {
        return __('filament-log-viewer::log.show.title', [
            'log' => Carbon::parse($this->record->date)->isoFormat('LL'),
        ]);
    }
}
