<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Entities;

use Boquizo\FilamentLogViewer\Utils\Parser;
use Carbon\Carbon;
use RuntimeException;
use TypeError;

class Entry extends Entity
{
    public string $env;

    public Carbon $datetime;

    public string $header;

    /** @var array<array-key, mixed> */
    public array $context = [];

    public function __construct(
        public string $level,
        string $header,
        public ?string $stack = null,
    ) {
        $this->header = $this->establishHeader($header);
    }

    private function establishHeader(string $header): string
    {
        $this->establishDatetime($header);

        return $this->clean($header);
    }

    private function clean(string $header): string
    {
        // Remove the date
        $pattern = Parser::DATETIME_PATTERN;

        $header = preg_replace("/\\[{$pattern}] /", '', $header) ?? $header;

        // Extract environment
        if (preg_match('/^[a-z]+.[A-Z]+:/', $header, $out)) {
            $this->env = explode('.', $out[0])[0];

            $header = trim(str_replace($out[0], '', $header));
        }

        // Extract context (Regex from https://stackoverflow.com/a/21995025)
        preg_match_all('/{(?:[^{}]|(?R))*}/x', $header, $out);
        if (isset($out[0][0])) {
            $context = json_decode($out[0][0], true);

            if (is_array($context)) {
                $header = str_replace($out[0][0], '', $header);
                $this->context = $context;
            }
        }

        return trim($header);
    }

    private function establishDatetime(string $header): void
    {
        $pattern = Parser::DATETIME_PATTERN;

        $datetime = preg_replace("/^\[({$pattern})].*/", '$1', $header) ?? $header;

        $parsed = Carbon::createFromFormat('Y-m-d H:i:s', $datetime);

        if (! $parsed instanceof Carbon) {
            throw new RuntimeException(
                "Unable to parse log datetime: {$datetime}",
            );
        }

        $this->datetime = $parsed;
    }

    public function stack(): string
    {
        if ($this->stack === null) {
            throw new TypeError('Entry stack cannot be null.');
        }

        return trim(htmlentities($this->stack));
    }

    public function context(int $options = JSON_PRETTY_PRINT): string
    {
        return json_encode($this->context, $options | JSON_THROW_ON_ERROR);
    }

    public function isSame(string $level): bool
    {
        return $this->level === $level;
    }

    /** @return EntryRow */
    public function toArray(): array
    {
        return [
            'env' => $this->env,
            'level' => $this->level,
            'datetime' => $this->datetime->format('Y-m-d H:i:s'),
            'header' => $this->header,
            'stack' => $this->stack(),
            'context' => $this->context(),
        ];
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options | JSON_THROW_ON_ERROR);
    }

    /** @return EntryRow */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
