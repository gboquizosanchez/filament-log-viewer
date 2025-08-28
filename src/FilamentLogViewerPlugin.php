<?php

namespace Boquizo\FilamentLogViewer;

use Boquizo\FilamentLogViewer\Entities\Log;
use Boquizo\FilamentLogViewer\Entities\LogCollection;
use Boquizo\FilamentLogViewer\Pages\ListLogs;
use Boquizo\FilamentLogViewer\Pages\ViewLog;
use Boquizo\FilamentLogViewer\UseCases\ClearLogUseCase;
use Boquizo\FilamentLogViewer\UseCases\DeleteLogUseCase;
use Boquizo\FilamentLogViewer\UseCases\DownloadLogUseCase;
use Boquizo\FilamentLogViewer\UseCases\DownloadZipUseCase;
use Boquizo\FilamentLogViewer\UseCases\ExtractLogByDateUseCase;
use Boquizo\FilamentLogViewer\Utils\Stats;
use Closure;
use Filament\Contracts\Plugin;
use Filament\FilamentManager;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use RuntimeException;
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

    public function getId(): string
    {
        return 'filament-log-viewer';
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

    public function driver(): string
    {
        $driver = Config::string('filament-log-viewer.driver');

        return match ($driver) {
            'raw', 'stack', 'daily' => $driver,
            default => 'daily',
        };
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

    public function getViewerStatsTable(): Stats
    {
        return Stats::make((new LogCollection())->stats());
    }

    public function getLogViewerRecord(): Log
    {
        $date = Session::get('filament-log-viewer-record');

        if ($date === null) {
            throw new RuntimeException('No log date found');
        }

        return ExtractLogByDateUseCase::execute($date);
    }

    /**
     * @throws \Throwable
     */
    public function deleteLog(string $date): bool
    {
        return DeleteLogUseCase::execute($date);
    }

    public function downloadLog(string $date): BinaryFileResponse
    {
        return DownloadLogUseCase::execute($date);
    }

    public function downloadLogs(array $files): BinaryFileResponse
    {
        return DownloadZipUseCase::execute($files);
    }

    public function clearLog(string $file): bool
    {
        return ClearLogUseCase::execute($file);
    }
}
