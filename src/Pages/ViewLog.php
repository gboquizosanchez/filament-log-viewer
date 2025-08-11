<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Pages;

use BackedEnum;
use Boquizo\FilamentLogViewer\Actions\BackAction;
use Boquizo\FilamentLogViewer\Actions\DeleteAction;
use Boquizo\FilamentLogViewer\Actions\DownloadAction;
use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Boquizo\FilamentLogViewer\Schema\Components\TabLevel;
use Boquizo\FilamentLogViewer\Tables\LogTable;
use Boquizo\FilamentLogViewer\Utils\Level;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\Resources\Concerns\HasTabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Locked;
use Override;

class ViewLog extends Page implements HasTable
{
    use HasTabs;
    use InteractsWithTable;

    #[Locked]
    public array|object|string|null $record;

    protected string $view = 'filament-log-viewer::view-log';

    protected static string|null|BackedEnum $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static bool $shouldRegisterNavigation = false;

    public static function table(Table $table): Table
    {
        return LogTable::configure($table);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getTabsContentComponent(),
                $this->table,
            ]);
    }

    #[Override]
    public function getHeaderActions(): array
    {
        return [
            DeleteAction::make(withTooltip: true),
            DownloadAction::make(withTooltip: true),
            BackAction::make(),
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

    public static function getSlug(?Panel $panel = null): string
    {
        $slug = Config::string('filament-log-viewer.resource.slug', 'logs');

        return "{$slug}/{record}";
    }

    public function mount(string $record): void
    {
        $this->record = (object) FilamentLogViewerPlugin::get()
            ->getLogsTableFiltered($record);

        Session::put('filament-log-viewer-record', $this->record->date);

        $this->loadDefaultActiveTab();
    }

    /** @return array<string, Tab> */
    public function getTabs(): array
    {
        // If there is only a level, and it's equal to 'all',
        // then we don't need to show the tabs. We just show the log.
        $exceptAll = Arr::except((array) $this->record, [Level::ALL]);

        if (in_array($this->record->all, $exceptAll, true)) {
            return [];
        }

        return [
            'all' => TabLevel::make(Level::ALL),
            'emergency' => TabLevel::make(Level::Emergency)
                ->when($this->record->emergency === 0,
                    fn (Tab $tab) => $tab->hidden()
                ),
            'alert' => TabLevel::make(Level::Alert)
                ->when($this->record->alert === 0,
                    fn (Tab $tab) => $tab->hidden()
                ),
            'critical' => TabLevel::make(Level::Critical)
                ->when($this->record->critical === 0,
                    fn (Tab $tab) => $tab->hidden()
                ),
            'error' => TabLevel::make(Level::Error)
                ->when($this->record->error === 0,
                    fn (Tab $tab) => $tab->hidden()
                ),
            'warning' => TabLevel::make(Level::Warning)
                ->when($this->record->warning === 0,
                    fn (Tab $tab) => $tab->hidden()
                ),
            'notice' => TabLevel::make(Level::Notice)
                ->when($this->record->notice === 0,
                    fn (Tab $tab) => $tab->hidden()
                ),
            'info' => TabLevel::make(Level::Info)
                ->when($this->record->info === 0,
                    fn (Tab $tab) => $tab->hidden()
                ),
            'debug' => TabLevel::make(Level::Debug)
                ->when($this->record->debug === 0,
                    fn (Tab $tab) => $tab->hidden()
                ),
        ];
    }

    public function getDefaultActiveTab(): string|int|null
    {
        return Level::ALL;
    }

    public function getTitle(): string
    {
        return __('filament-log-viewer::log.show.title', [
            'log' => Carbon::parse($this->record->date ?? null)->isoFormat('LL'),
        ]);
    }
}
