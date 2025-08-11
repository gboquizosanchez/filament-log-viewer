<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Entities;

use Boquizo\FilamentLogViewer\Utils\Level;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;
use Override;
use SplFileInfo;

class Log extends Entity
{
    private SplFileInfo $file;

    private EntryCollection $entries;

    public static function make(string $date, string $path, string $raw): self
    {
        return new self($date, $path, $raw);
    }

    public function __construct(
        private readonly string $date,
        private readonly string $path,
        string $raw,
    ) {
        $this->file = new SplFileInfo($path);
        $this->entries = EntryCollection::load($raw);
    }

    public function path(): string
    {
        return $this->path;
    }

    public function file(): SplFileInfo
    {
        return $this->file;
    }

    public function size(): string
    {
        return Number::fileSize($this->file->getSize());
    }

    public function createdAt(): Carbon
    {
        return Carbon::createFromTimestamp($this->file()->getATime());
    }

    public function updatedAt(): Carbon
    {
        return Carbon::createFromTimestamp($this->file()->getMTime());
    }

    public function entries(string $level = Level::ALL): EntryCollection
    {
        return $level === Level::ALL
            ? $this->entries
            : $this->level($level);
    }

    public function toModel(): array
    {
        return $this->entries()
            ->map(fn (Entry $entry): array => $entry->toArray())
            ->all() ?? [];
    }

    public function level(string $level): EntryCollection
    {
        return $this->entries->filterByLevel($level);
    }

    public function stats(): array
    {
        return $this->entries->stats();
    }

    /** @param  int  $options */
    #[Override]
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @return array{
     *     date: string,
     *     path: string,
     *     entries: array{
     *        env: string,
     *        level: string,
     *        datetime: \Carbon\Carbon::class,
     *        header: string,
     *        stack: string,
     *        context: string
     *     }[]
     *  }
     */
    #[Override]
    public function toArray(): array
    {
        return [
            'date' => $this->date,
            'path' => $this->path,
            'entries' => $this->entries->toArray(),
        ];
    }
}
