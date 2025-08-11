<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\UseCases;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;

class ReadLogUseCase
{
    public static function execute(string $date): string
    {
        return (new self())($date);
    }

    public function __invoke(string $date): string
    {
        try {
            $log = (new Filesystem())->get(
                ExtractLogPathUseCase::execute($date)
            );
        } catch (FileNotFoundException $e) {
            throw new RuntimeException($e->getMessage());
        }

        return $log;
    }
}
