<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;

class ReadLogAction
{
    public static function execute(string $date): string
    {
        return (new self())($date);
    }

    public function __invoke(string $date): string
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
