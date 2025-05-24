<?php

namespace Boquizo\FilamentLogViewer;

use Boquizo\FilamentLogViewer\Actions\DeleteLogAction;
use Boquizo\FilamentLogViewer\Actions\DownloadLogAction;
use Boquizo\FilamentLogViewer\Actions\DownloadZipAction;
use Boquizo\FilamentLogViewer\Actions\ExtractLogByDateAction;
use Boquizo\FilamentLogViewer\Entities\Log;
use Boquizo\FilamentLogViewer\Entities\LogCollection;
use Boquizo\FilamentLogViewer\Pages\ListLogs;
use Boquizo\FilamentLogViewer\Pages\ViewLog;
use Boquizo\FilamentLogViewer\Tables\StatsTable;
use Closure;
use Filament\Contracts\Plugin;
use Filament\FilamentManager;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FilamentLogViewerPlugin implements Plugin
{
    use EvaluatesClosures;

    protected bool|Closure $authorizeUsing = true;

    protected string $viewLog = ViewLog::class;

    protected string $listLogs = ListLogs::class;

    protected string|Closure|null $navigationGroup = null;

    protected int|Closure $navigationSort = 1;

    protected string|Closure $navigationIcon = 'heroicon-o-document-text';

    protected string|Closure|null $navigationLabel = null;

    protected string|Closure $slug = 'logs';

    public function getId(): string
    {
        return 'filament-laravel-log';
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): Plugin|FilamentManager|static
    {
        return filament(app(static::class)->getId());
    }

    public function register(Panel $panel): void
    {
        $panel
            ->pages([
                $this->listLogs,
                $this->viewLog,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public function authorize(bool|Closure $callback = true): static
    {
        $this->authorizeUsing = $callback;

        return $this;
    }

    public function isAuthorized(): bool
    {
        return $this->evaluate($this->authorizeUsing) === true;
    }

    public function listLogs(string $listLogs): static
    {
        $this->listLogs = $listLogs;

        return $this;
    }

    public function getListLog(): string
    {
        return $this->evaluate($this->listLogs);
    }

    public function viewLog(string $viewLog): static
    {
        $this->viewLog = $viewLog;

        return $this;
    }

    public function getViewLog(): string
    {
        return $this->evaluate($this->viewLog);
    }

    public function navigationGroup(string|Closure|null $navigationGroup): static
    {
        $this->navigationGroup = $navigationGroup;

        return $this;
    }

    public function getNavigationGroup(): string
    {
        return $this->evaluate($this->navigationGroup) ?? __('filament-log-viewer::log.navigation.group');
    }

    public function navigationSort(int|Closure $navigationSort): static
    {
        $this->navigationSort = $navigationSort;

        return $this;
    }

    public function getNavigationSort(): int
    {
        return $this->evaluate($this->navigationSort);
    }

    public function navigationIcon(string|Closure $navigationIcon): static
    {
        $this->navigationIcon = $navigationIcon;

        return $this;
    }

    public function getNavigationIcon(): string
    {
        return $this->evaluate($this->navigationIcon);
    }

    public function navigationLabel(string|Closure|null $navigationLabel): static
    {
        $this->navigationLabel = $navigationLabel;

        return $this;
    }

    public function getNavigationLabel(): string
    {
        return $this->evaluate($this->navigationLabel)
            ?? __('filament-log-viewer::log.navigation.label');
    }

    /**
     * Commented until the issue is fixed in filamentphp/filament.
     *
     * It doesn't allow changing the plugin slug.
     *
     * @see https://github.com/filamentphp/filament/issues/16037
     * @see https://github.com/gboquizosanchez/filament-log-viewer/issues/1
     */
    //    public function slug(string|Closure $slug): static
    //    {
    //        $this->slug = $slug;
    //
    //        return $this;
    //    }

    public function getSlug(): string
    {
        return $this->evaluate($this->slug);
    }

    public function getViewerStatsTable(): StatsTable
    {
        return StatsTable::make((new LogCollection())->stats());
    }

    public function getLogViewerRecord(): Log
    {
        return ExtractLogByDateAction::execute(
            Session::get('filament-log-viewer-record'),
        );
    }

    /**
     * @throws \Throwable
     */
    public function deleteLog(string $date): bool
    {
        return DeleteLogAction::execute($date);
    }

    public function downloadLog(string $date): BinaryFileResponse
    {
        return DownloadLogAction::execute($date);
    }

    public function downloadLogs(array $files): BinaryFileResponse
    {
        return DownloadZipAction::execute($files);
    }
}
