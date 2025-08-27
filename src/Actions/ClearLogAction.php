<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Exception;
use Illuminate\Support\Facades\File;

class ClearLogAction
{
    public static function execute(string $file): bool
    {
        try {
            File::put(ExtractLogPathAction::execute($file), '');

            return true;
        } catch (Exception) {
            return false;
        }
    }
}
