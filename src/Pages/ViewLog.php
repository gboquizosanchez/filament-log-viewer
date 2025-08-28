<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Pages;

use Boquizo\FilamentLogViewer\Actions\BackAction;
use Boquizo\FilamentLogViewer\Actions\ClearLogAction;
use Boquizo\FilamentLogViewer\Actions\DeleteAction;
use Boquizo\FilamentLogViewer\Actions\DownloadAction;
use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Models\LogStat;
use Boquizo\FilamentLogViewer\Schema\Components\TabLevel;
use Boquizo\FilamentLogViewer\Tables\EntriesTable;
use Boquizo\FilamentLogViewer\UseCases\ParseDateUseCase;
use Boquizo\FilamentLogViewer\Utils\Level;
use Filament\Pages\Page;
use Filament\Resources\Components\Tab;
use Filament\Resources\Concerns\HasTabs;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Locked;
use Override;

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
        return EntriesTable::configure($table);
    }

    #[Override]
    public function getHeaderActions(): array
    {
        return [
            DownloadAction::make(withTooltip: true),
            ClearLogAction::make(withTooltip: true),
            DeleteAction::make(withTooltip: true),
            BackAction::make(),
        ];
    }

    public static function canAccess(): bool
    {
        return FilamentLogViewerPlugin::get()->isAuthorized();
    }

    public static function getSlug(): string
    {
        $slug = Config::string('filament-log-viewer.resource.slug', 'logs');

        return "{$slug}/{record}";
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
        // If there is only a level, and it's equal to 'all',
        // then we don't need to show the tabs. We just show the log.
        $exceptAll = Arr::except($this->record->toArray(), [Level::ALL]);

        if (in_array($this->record->all, $exceptAll, true)) {
            return [];
        }

        return [
            'all' => TabLevel::make(Level::ALL)
                ->badge(fn () => $this->record->all),
            'emergency' => TabLevel::make(Level::Emergency)
                ->badge(fn () => $this->record->emergency)
                ->when($this->record->emergency === 0,
                    fn (Tab $tab) => $tab->extraAttributes([
                        'class' => 'hidden',
                    ]),
                ),
            'alert' => TabLevel::make(Level::Alert)
                ->badge(fn () => $this->record->alert)
                ->when($this->record->alert === 0,
                    fn (Tab $tab) => $tab->extraAttributes([
                        'class' => 'hidden',
                    ]),
                ),
            'critical' => TabLevel::make(Level::Critical)
                ->badge(fn () => $this->record->critical)
                ->when($this->record->critical === 0,
                    fn (Tab $tab) => $tab->extraAttributes([
                        'class' => 'hidden',
                    ]),
                ),
            'error' => TabLevel::make(Level::Error)
                ->badge(fn () => $this->record->error)
                ->when($this->record->error === 0,
                    fn (Tab $tab) => $tab->extraAttributes([
                        'class' => 'hidden',
                    ]),
                ),
            'warning' => TabLevel::make(Level::Warning)
                ->badge(fn () => $this->record->warning)
                ->when($this->record->warning === 0,
                    fn (Tab $tab) => $tab->extraAttributes([
                        'class' => 'hidden',
                    ]),
                ),
            'notice' => TabLevel::make(Level::Notice)
                ->badge(fn () => $this->record->notice)
                ->when($this->record->notice === 0,
                    fn (Tab $tab) => $tab->extraAttributes([
                        'class' => 'hidden',
                    ]),
                ),
            'info' => TabLevel::make(Level::Info)
                ->badge(fn () => $this->record->info)
                ->when($this->record->info === 0,
                    fn (Tab $tab) => $tab->extraAttributes([
                        'class' => 'hidden',
                    ]),
                ),
            'debug' => TabLevel::make(Level::Debug)
                ->badge(fn () => $this->record->debug)
                ->when($this->record->debug === 0,
                    fn (Tab $tab) => $tab->extraAttributes([
                        'class' => 'hidden',
                    ]),
                ),
        ];
    }

    public function getDefaultActiveTab(): string|int|null
    {
        return Level::ALL;
    }

    public function getTitle(): string
    {
        $date = $this->record->date ?? null;

        return __('filament-log-viewer::log.show.title', [
            'log' => ParseDateUseCase::execute($date),
        ]);
    }
}
