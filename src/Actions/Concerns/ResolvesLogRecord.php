<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions\Concerns;

use Boquizo\FilamentLogViewer\Pages\ListLogs;
use Boquizo\FilamentLogViewer\Pages\ViewLog;
use Filament\Actions\Action;
use UnexpectedValueException;

trait ResolvesLogRecord
{
    private static function resolveLogDate(
        Action $action,
        ViewLog | ListLogs $livewire,
    ): string {
        $record = $action->getRecord();

        if (is_array($record) && is_string($record['date'] ?? null)) {
            return $record['date'];
        }

        if ($livewire instanceof ViewLog) {
            return $livewire->getLogRow()['date'];
        }

        throw new UnexpectedValueException(
            'A log action requires a record with a date.',
        );
    }
}
