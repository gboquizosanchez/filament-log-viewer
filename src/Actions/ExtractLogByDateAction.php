<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Arcanedev\LogViewer\Entities\Log;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
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
            throw new \RuntimeException("Log not found in this date [{$date}]");
        }

        return new Log($date, $dates[$date], $this->read($date));
    }

    private function read(string $date): string
    {
        try {
            $log = (new Filesystem())->get(
                ExtractLogPathAction::execute($date)
            );
        } catch (FileNotFoundException $e) {
            throw new RuntimeException($e->getMessage());
        }

        return $log;
    }
}
