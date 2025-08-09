<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\UseCases;

use Illuminate\Filesystem\Filesystem;
use RuntimeException;

class DeleteLogAction
{
    /**
     * @throws \Throwable
     */
    public static function execute(string $date): true
    {
        return (new self())($date);
    }

    /**
     * @throws \Throwable
     */
    public function __invoke(string $date): true
    {
        $path = ExtractLogPathAction::execute($date);

        $system = new Filesystem();

        throw_unless(
            $system->delete($path),
            new RuntimeException('There was an error deleting the log.'),
        );

        return true;
    }
}
