<?php

namespace Boquizo\FilamentLogViewer;

use BackedEnum;
use Boquizo\FilamentLogViewer\Entities\Log;
use Boquizo\FilamentLogViewer\Entities\LogCollection;
use Boquizo\FilamentLogViewer\Exceptions\TimezoneNotValidException;
use Boquizo\FilamentLogViewer\Pages\ListLogs;
use Boquizo\FilamentLogViewer\Pages\ViewLog;
use Boquizo\FilamentLogViewer\UseCases\ClearLogUseCase;
use Boquizo\FilamentLogViewer\UseCases\DeleteLogUseCase;
use Boquizo\FilamentLogViewer\UseCases\DownloadLogUseCase;
use Boquizo\FilamentLogViewer\UseCases\DownloadZipUseCase;
use Boquizo\FilamentLogViewer\UseCases\ExtractLogByDateUseCase;
use Boquizo\FilamentLogViewer\Utils\Stats;
use Closure;
use DateTimeZone;
use Filament\Contracts\Plugin;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Config;
use RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use TypeError;
use UnitEnum;

class FilamentLogViewerPlugin implements Plugin
{
    use EvaluatesClosures;

    public const ID = 'filament-log-viewer';

    protected bool | Closure $authorizeUsing = true;

    /** @var class-string<Page> */
    protected string $viewLog = ViewLog::class;

    /** @var class-string<Page> */
    protected string $listLogs = ListLogs::class;

    protected string | Closure | UnitEnum | null $navigationGroup = null;

    protected int | Closure $navigationSort = 1;

    protected string | Closure | BackedEnum | null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected string | Closure | null $navigationLabel = null;

    protected ?string $timezone = null;

    public function getId(): string
    {
        return self::ID;
    }

    public static function make(): static
    {
        $plugin = app(static::class);

        if (! $plugin instanceof static) {
            throw new RuntimeException('Unable to resolve the filament-log-viewer plugin.');
        }

        return $plugin;
    }

    public static function get(): static
    {
        $plugin = filament(self::ID);

        if (! $plugin instanceof static) {
            throw new RuntimeException(
                'The filament-log-viewer plugin is not registered on the current panel.',
            );
        }

        return $plugin;
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

    /** @return 'raw'|'single'|'daily' */
    public function driver(): string
    {
        $driver = Config::string('filament-log-viewer.driver');

        return match ($driver) {
            'raw', 'single', 'daily' => $driver,
            default => 'daily',
        };
    }

    public function authorize(bool | Closure $callback = true): static
    {
        $this->authorizeUsing = $callback;

        return $this;
    }

    public function isAuthorized(): bool
    {
        return $this->evaluate($this->authorizeUsing) === true;
    }

    /** @param class-string<Page> $listLogs */
    public function listLogs(string $listLogs): static
    {
        $this->listLogs = $listLogs;

        return $this;
    }

    public function getListLog(): string
    {
        return $this->evaluate($this->listLogs);
    }

    /** @param class-string<Page> $viewLog */
    public function viewLog(string $viewLog): static
    {
        $this->viewLog = $viewLog;

        return $this;
    }

    public function viewInModal(bool $viewInModal = true): static
    {
        Config::set('filament-log-viewer.view_in_modal', $viewInModal);

        return $this;
    }

    public function getViewLog(): string
    {
        return $this->evaluate($this->viewLog);
    }

    public function navigationGroup(string | Closure | UnitEnum | null $navigationGroup): static
    {
        $this->navigationGroup = $navigationGroup;

        return $this;
    }

    public function getNavigationGroup(): string
    {
        $group = $this->evaluate($this->navigationGroup);

        if ($group instanceof UnitEnum) {
            if (method_exists($group, 'getLabel')) {
                return $this->requireString($group->getLabel(), 'navigation group label');
            }

            return $group->name;
        }

        if (is_string($group)) {
            return $group;
        }

        return $this->requireString(
            __('filament-log-viewer::log.navigation.group'),
            'translated navigation group',
        );
    }

    public function navigationSort(int | Closure $navigationSort): static
    {
        $this->navigationSort = $navigationSort;

        return $this;
    }

    public function getNavigationSort(): int
    {
        $sort = $this->evaluate($this->navigationSort);

        if (! is_int($sort)) {
            throw new TypeError('The evaluated navigation sort must be an integer.');
        }

        return $sort;
    }

    public function navigationIcon(string | Closure | BackedEnum $navigationIcon): static
    {
        $this->navigationIcon = $navigationIcon;

        return $this;
    }

    public function getNavigationIcon(): string | BackedEnum | null
    {
        $icon = $this->evaluate($this->navigationIcon);

        if (! is_string($icon) && ! $icon instanceof BackedEnum && $icon !== null) {
            throw new TypeError(
                'The evaluated navigation icon must be a string, backed enum, or null.',
            );
        }

        return $icon;
    }

    public function navigationLabel(string | Closure | null $navigationLabel): static
    {
        $this->navigationLabel = $navigationLabel;

        return $this;
    }

    public function getNavigationLabel(): string
    {
        $label = $this->evaluate($this->navigationLabel)
            ?? __('filament-log-viewer::log.navigation.label');

        return $this->requireString($label, 'navigation label');
    }

    public function getViewerStats(): Stats
    {
        return Stats::make((new LogCollection)->stats());
    }

    /** @return LogRow|array{} */
    public function getLogsTableFiltered(string $date): array
    {
        return collect($this->getLogsTableRecords())
            ->filter(static fn (array $row): bool => $row['date'] === $date)
            ->values()
            ->first() ?? [];
    }

    /** @return list<LogRow> */
    public function getLogsTableRecords(): array
    {
        $rows = $this
            ->getViewerStats()
            ->rows;

        return array_values($rows);
    }

    public function getLogViewerRecord(string $date): Log
    {
        return ExtractLogByDateUseCase::execute($date);
    }

    public function timezone(string $timezone): static
    {
        if (! in_array($timezone, DateTimeZone::listIdentifiers(), true)) {
            throw new TimezoneNotValidException($timezone);
        }

        $this->timezone = $timezone;

        return $this;
    }

    public function getTimezone(): string
    {
        return $this->timezone ?? Config::string('app.timezone');
    }

    private function requireString(mixed $value, string $name): string
    {
        if (! is_string($value)) {
            throw new TypeError("The {$name} must be a string.");
        }

        return $value;
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

    /** @param list<string> $files */
    public function downloadLogs(array $files): BinaryFileResponse
    {
        return DownloadZipUseCase::execute($files);
    }

    public function clearLog(string $file): bool
    {
        return ClearLogUseCase::execute($file);
    }
}
