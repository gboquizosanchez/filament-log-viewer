<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Illuminate\Support\Facades\File;

class ClearLogAction
{
    public static function execute(string $file): bool
    {
        return (bool) File::put(ExtractLogPathAction::execute($file), '');
    }
}
