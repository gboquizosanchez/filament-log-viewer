<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Boquizo\FilamentLogViewer\Entities\Log;
use RuntimeException;

class ExtractLogByDateAction
{
    public static function execute(string $date): Log
    {
        return (new self())($date);
    }

    public function __invoke(string $date): Log
    {
        $dates = ExtractDatesAction::execute();

        if (!isset($dates[$date])) {
            throw new RuntimeException("Log not found in this date [{$date}]");
        }

        return new Log($date, $dates[$date], ReadLogAction::execute($date));
    }
}
